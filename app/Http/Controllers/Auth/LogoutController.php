<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Service\Auth\LogoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * @tags Usuário
 */
class LogoutController extends Controller
{
    public function __construct(private readonly LogoutService $logoutService) {}

    /**
     * Logout de usuário
     */
    public function __invoke(): JsonResponse
    {
        try {
            $this->logoutService->executar();
            return response()->noContent();
        } catch (Exception $exception) {
            Log::critical('Controller: ' . self::class, ['exception' => $exception->getMessage()]);
            return response()->json(
                ['message' => config('mensagens.generico.erro')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
