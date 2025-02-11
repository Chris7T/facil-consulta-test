<?php

namespace App\Service\Auth;

use App\Exception\Usuario\UsuarioCredenciaisInvalidoException;
use App\Repository\UsuarioRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function __construct(private readonly UsuarioRepository $usuarioRepository) {}

    public function handle(array $dados): array
    {
        $usuarioComEmail = $this->usuarioRepository->buscarPeloEmail($dados['email']);

        if (is_null($usuarioComEmail)) {
            throw new UsuarioCredenciaisInvalidoException();
        }

        if (!Hash::check($dados['senha'], $usuarioComEmail->senha)) {
            throw new UsuarioCredenciaisInvalidoException();
        }

        $token = Auth::login($usuarioComEmail);

        return [
            'token_acesso' => $token,
            'tipo_token' => 'bearer',
            'expira_em' => Auth::factory()->getTTL() * 60
        ];
    }
}
