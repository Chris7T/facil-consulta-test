<?php

namespace App\Service\Auth;

use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function executar(): void
    {
        Auth::logout();
    }
}