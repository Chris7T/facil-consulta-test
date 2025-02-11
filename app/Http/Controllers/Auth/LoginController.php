<?php

namespace App\Http\Controllers\Auth;

use App\Exception\Usuario\UsuarioCredenciaisInvalidoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Service\Auth\LoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(private readonly LoginService $loginService) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $dados = $this->loginService->handle($request->validated());

            return response()->json($dados);
        } catch (UsuarioCredenciaisInvalidoException) {
            return response()->json(
                ['message' => config('mensagens.usuario.credenciais_invalido')],
                Response::HTTP_NOT_FOUND
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
