<?php

namespace App\Http\Controllers\Auth;

use App\Exception\Usuario\UsuarioEmailInvalidoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CadastrarRequest;
use App\Service\Auth\CadastrarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * @tags Usuário
 */
class CadastrarController extends Controller
{
    public function __construct(private readonly CadastrarService $cadastrarService) {}

    /**
     * Cadastro de usuário
     * 
     */
    public function __invoke(CadastrarRequest $request): JsonResponse|Response
    {
        try {
            $dados = $this->cadastrarService->handle($request->validated());

            return response()->json($dados);
        } catch (UsuarioEmailInvalidoException) {
            return response()->json(
                ['message' => config('mensagens.usuario.email_invalido')],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (Exception $exception) {
            Log::critical('Controller: ' . self::class, ['exception' => $exception->getMessage()]);
            return response()->json(
                ['message' => config('mensagens.generico.erro')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}