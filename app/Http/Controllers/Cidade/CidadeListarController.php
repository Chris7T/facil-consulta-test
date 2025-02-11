<?php

namespace App\Http\Controllers\Cidade;

use App\Http\Requests\Cidade\CidadeListarRequest;
use App\Http\Resource\CidadeResource;
use App\Service\Cidade\CidadeListarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Cidade
 */
class CidadeListarController
{
    public function __construct(private readonly CidadeListarService $cidadeListarService) {}

    /**
     * Listar Cidades
     * 
     * @response AnonymousResourceCollection<LengthAwarePaginator<CidadeResource>>
     */
    public function __invoke(CidadeListarRequest $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $data = $this->cidadeListarService->executar($request->query('nome'));
            return CidadeResource::collection($data);
        } catch (Exception $exception) {
            Log::critical('Controller: ' . self::class, ['exception' => $exception->getMessage()]);
            return response()->json(
                ['message' => config('mensagens.generico.erro')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
