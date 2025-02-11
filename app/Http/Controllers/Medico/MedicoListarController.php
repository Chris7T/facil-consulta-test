<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicoListarRequest;
use App\Http\Resource\CidadeResource;
use App\Service\Medico\MedicoListarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MedicoListarController extends Controller
{
    public function __construct(private readonly MedicoListarService $medicoListarService) {}

    public function __invoke(MedicoListarRequest $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $data = $this->medicoListarService->executar($request->query('nome'));
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
