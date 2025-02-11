<?php

namespace App\Repository;

use App\Models\Usuario;

class UsuarioRepository
{
    public function criar(string $email, string $nome, string $senhaComHash): Usuario
    {
        return Usuario::create([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senhaComHash,
        ]);
    }

    public function buscarPeloEmail(string $email): ?Usuario
    {
        return Usuario::where('email', $email)->first();
    }
}
