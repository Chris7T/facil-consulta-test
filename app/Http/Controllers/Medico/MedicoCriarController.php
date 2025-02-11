<?php

namespace App\Http\Controllers\Medico;

use App\Exception\Cidade\CidadeNaoEncontradaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicoCriarRequest;
use App\Service\Medico\MedicoCriarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * @tags Médico
 */
class MedicoCriarController extends Controller
{
    public function __construct(private readonly MedicoCriarService $medicoCriarService) {}

    /**
     * Criar médico
     * 
     */
    public function __invoke(MedicoCriarRequest $request): JsonResponse|Response
    {
        try {
            $this->medicoCriarService->executar($request->validated());

            return response()->noContent();
        } catch (CidadeNaoEncontradaException) {
            return response()->json(
                ['message' => config('mensagens.cidade.nao_encontrado')],
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
