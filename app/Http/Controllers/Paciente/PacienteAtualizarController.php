<?php

namespace App\Http\Controllers\Paciente;

use App\Exception\Paciente\PacienteNaoEncontradoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Paciente\PacienteAtualizarRequest;
use App\Http\Resource\PacienteResource;
use App\Service\Paciente\PacienteAtualizarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class PacienteAtualizarController extends Controller
{
    public function __construct(private readonly PacienteAtualizarService $pacienteAtualizarService) {}

    public function __invoke(PacienteAtualizarRequest $request, int $id): JsonResponse|PacienteResource
    {
        try {
            $paciente = $this->pacienteAtualizarService->executar($request->validated(), $id);

            return PacienteResource::make($paciente);
        } catch (PacienteNaoEncontradoException $exception) {
            return response()->json(
                ['message' => config('mensagens.paciente.nao_encontrado')],
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
