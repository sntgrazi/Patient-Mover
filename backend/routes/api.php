<?php

use App\Http\Controllers\LocaisController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitacaoTransporte;
use App\Http\Controllers\IncidentesController; 
use App\Http\Controllers\SenhaResetController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('pacientes', [PacientesController::class, 'index']);
Route::post('pacientes', [PacientesController::class, 'store']);
Route::put('pacientes/{id}', [PacientesController::class, 'update']);
Route::delete('pacientes/{id}', [PacientesController::class, 'destroy']);

Route::post('login', [AuthController::class, 'login']);

Route::get('users',[UserController::class, 'index']);

Route::post('users/admin', [UserController::class, 'storeAdmin']);
Route::post('users/maqueiro', [UserController::class, 'storeMaqueiro']);
Route::post('password/temporary', [SenhaResetController::class, 'senhaTemporariaReset']);

Route::middleware('auth:api')->post('/reset-password', [AuthController::class, 'resetPassword']);

Route::put('users/{id}', [UserController::class, 'update']);

Route::post('users/{id}/deactivate', [UserController::class, 'deactivate']);
Route::post('users/{id}/activate', [UserController::class, 'activate']);


Route::get('transportes', [SolicitacaoTransporte::class, 'index']);

Route::get('transportes/{id}/disponiveis', [SolicitacaoTransporte::class, 'getSolicitacoesDisponiveis']);

Route::post('solicitacoes', [SolicitacaoTransporte::class, 'store']);

Route::put('/solicitacoes/{id}/aceitar', [SolicitacaoTransporte::class, 'aceitarTransporte']);

Route::post('/solicitacoes/{id}/recusar', [SolicitacaoTransporte::class, 'recusarTransporte']);


Route::put('/solicitacoes/{id}', [SolicitacaoTransporte::class, 'TransporteMaqueiroId']);
Route::get('locais', [LocaisController::class, 'index']);

Route::get('incidentes', [IncidentesController::class, 'index']);