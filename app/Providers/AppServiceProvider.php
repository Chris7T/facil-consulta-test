<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::routes(function () {
            return [
                '*' // Documenta todas as rotas
                // Ou especifique rotas específicas
                // route('api.users.index'),
                // route('api.users.store')
            ];
        });
    }
}
