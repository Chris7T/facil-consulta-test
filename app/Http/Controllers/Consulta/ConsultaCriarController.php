<?php

namespace App\Http\Controllers\Consulta;

use App\Exception\Medico\MedicoNaoEncontradoException;
use App\Exception\Paciente\PacienteNaoEncontradoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Consulta\ConsultaCriarRequest;
use App\Http\Resource\ConsultaResource;
use App\Service\Consulta\ConsultaCriarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * @tags Consulta
 */
class ConsultaCriarController extends Controller
{
    public function __construct(private readonly ConsultaCriarService $consultaCriarService) {}

    /**
     * Criar consulta
     * 
     * @response ConsultaResource
     */
    public function __invoke(ConsultaCriarRequest $request): JsonResponse|ConsultaResource
    {
        try {
            $dados = $this->consultaCriarService->executar($request->validated());

            return response()->json(new ConsultaResource($dados), Response::HTTP_CREATED);
        } catch (MedicoNaoEncontradoException) {
            return response()->json(
                ['message' => config('mensagens.medico.nao_encontrado')],
                Response::HTTP_NOT_FOUND
            );
        } catch (PacienteNaoEncontradoException) {
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
