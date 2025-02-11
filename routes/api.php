<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CadastrarController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Cidade\CidadeListarController;
use App\Http\Controllers\Cidade\CidadeListarMedicoController;
use App\Http\Controllers\Consulta\ConsultaCriarController;
use App\Http\Controllers\Medico\MedicoCriarController;
use App\Http\Controllers\Medico\MedicoListarController;
use App\Http\Controllers\Medico\MedicoListarPacienteController;
use App\Http\Controllers\Paciente\PacienteAtualizarController;
use App\Http\Controllers\Paciente\PacienteCriarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', LoginController::class);
    Route::post('cadastrar', CadastrarController::class);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', LogoutController::class);
    });
});

Route::get('cidades', CidadeListarController::class);

Route::get('cidades/{cidadeId}/medicos', CidadeListarMedicoController::class)->where('cidadeId', '[0-9]+');

Route::get('medicos', MedicoListarController::class)->where('cidadeId', '[0-9]+');

Route::middleware('auth:api')->post('medicos/consulta', ConsultaCriarController::class);

Route::middleware('auth:api')->post('medicos', MedicoCriarController::class);

Route::middleware('auth:api')->get('medicos/{medicoId}/pacientes', MedicoListarPacienteController::class);

Route::middleware('auth:api')->put('pacientes/{pacienteId}', PacienteAtualizarController::class);

Route::middleware('auth:api')->post('pacientes', PacienteCriarController::class);