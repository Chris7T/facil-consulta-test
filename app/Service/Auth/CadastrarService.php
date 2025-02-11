<?php

namespace App\Service\Auth;

use App\Exception\Usuario\UsuarioEmailInvalidoException;
use App\Repository\UsuarioRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CadastrarService
{
    public function __construct(private readonly UsuarioRepository $usuarioRepository) {}

    public function handle(array $data): array
    {
        $usuarioComEmail = $this->usuarioRepository->buscarPeloEmail($data['email']);

        if (!is_null($usuarioComEmail)) {
            throw new UsuarioEmailInvalidoException();
        }
        
        $senhaComHash = Hash::make($data['senha']);
        $usuario = $this->usuarioRepository->criar($data['email'], $data['nome'], $senhaComHash);
        $token = Auth::login($usuario);

        return [
            'token_acesso' => $token,
            'tipo_token' => 'bearer',
            'expira_em' => Auth::factory()->getTTL() * 60
        ];
    }
}
