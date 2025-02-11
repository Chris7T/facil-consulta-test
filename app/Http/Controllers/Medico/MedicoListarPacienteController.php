<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicoListarPacienteRequest;
use App\Http\Resource\PacienteComConsultaResource;
use App\Service\Paciente\PacienteListarPorMedicoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @tags Médico
 */
class MedicoListarPacienteController extends Controller
{
    public function __construct(private readonly PacienteListarPorMedicoService $pacienteListarPorMedicoService) {}

    /**
     * Listar pacientes do médico
     * 
     * @response AnonymousResourceCollection<LengthAwarePaginator<PacienteComConsultaResource>>
     */
    public function __invoke(MedicoListarPacienteRequest $request, int $medicoId): AnonymousResourceCollection|JsonResponse
    {
        try {
            $data = $this->pacienteListarPorMedicoService->executar($medicoId, $request->query('apenas-agendadas', false), $request->query('nome'));

            return PacienteComConsultaResource::collection($data);
        } catch (Exception $exception) {
            Log::critical('Controller: ' . self::class, ['exception' => $exception->getMessage()]);
            return response()->json(
                ['message' => config('mensagens.generico.erro')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
