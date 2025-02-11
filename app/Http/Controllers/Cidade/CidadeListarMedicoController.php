<?php

namespace App\Http\Controllers\Cidade;

use App\Exception\Cidade\CidadeNaoEncontradaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicoListarRequest;
use App\Http\Resource\MedicoResource;
use App\Service\Medico\MedicoListarPorCidadeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Cidade
 */
class CidadeListarMedicoController extends Controller
{
    public function __construct(private readonly MedicoListarPorCidadeService $medicoListarPorCidadeService) {}

    /**
     * Listar médicos da cidade
     * 
     * @response AnonymousResourceCollection<LengthAwarePaginator<MedicoResource>>
     */
    public function __invoke(MedicoListarRequest $request, int $cidadeId): AnonymousResourceCollection|JsonResponse
    {
        try {
            $data = $this->medicoListarPorCidadeService->executar($cidadeId, $request->query('nome'));
            return MedicoResource::collection($data);
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
