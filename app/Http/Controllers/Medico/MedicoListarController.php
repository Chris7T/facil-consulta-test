<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicoListarRequest;
use App\Http\Resource\MedicoResource;
use App\Service\Medico\MedicoListarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Médico
 */
class MedicoListarController extends Controller
{
    public function __construct(private readonly MedicoListarService $medicoListarService) {}

    /**
     * Listar médico
     * 
     * @response AnonymousResourceCollection<LengthAwarePaginator<MedicoResource>>
     */
    public function __invoke(MedicoListarRequest $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $data = $this->medicoListarService->executar($request->query('nome'));
            return MedicoResource::collection($data);
        } catch (Exception $exception) {
            Log::critical('Controller: ' . self::class, ['exception' => $exception->getMessage()]);
            return response()->json(
                ['message' => config('mensagens.generico.erro')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
