<?php

namespace App\Http\Controllers\Paciente;

use App\Exception\Paciente\PacienteCpfInvalidoException;
use App\Http\Requests\Paciente\PacienteCriarRequest;
use App\Http\Controllers\Controller;
use App\Http\Resource\PacienteResource;
use App\Service\Paciente\PacienteCriarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class PacienteCriarController extends Controller
{
    public function __construct(private readonly PacienteCriarService $pacienteCriarService) {}

    public function __invoke(PacienteCriarRequest $request): JsonResponse
    {
        try {
            $paciente = $this->pacienteCriarService->executar($request->validated());
            return response()->json(new PacienteResource($paciente), Response::HTTP_CREATED);
        } catch (PacienteCpfInvalidoException $exception) {
            return response()->json(
                ['message' => config('mensagens.paciente.cpf_invalido')],
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
