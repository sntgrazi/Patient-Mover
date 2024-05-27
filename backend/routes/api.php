<?php

use App\Http\Controllers\PacientesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitacaoTransporte;
use App\Http\Controllers\IncidentesController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('pacientes', [PacientesController::class, 'index']);
Route::post('pacientes', [PacientesController::class, 'store']);
Route::put('pacientes/{id}', [PacientesController::class, 'update']);
Route::delete('pacientes/{id}', [PacientesController::class, 'destroy']);

Route::post('login', [AuthController::class, 'login']);
Route::get('users',[UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);

Route::get('solicitacoes', [SolicitacaoTransporte::class, 'index']);
Route::post('solicitacoes', [SolicitacaoTransporte::class, 'store']);

Route::get('incidentes', [IncidentesController::class, 'index']);