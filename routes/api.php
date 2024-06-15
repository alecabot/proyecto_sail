<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('gestion/v2')->group(function() {
        Route::post('alumno/crear', [App\Http\Controllers\ApiController::class, 'crearAlumno'])->name('gestion.crearAlumno');
        Route::patch('alumno/editar', [App\Http\Controllers\ApiController::class, 'editarAlumno'])->name('gestion.editarAlumno');
        Route::delete('alumno/eliminar', [App\Http\Controllers\ApiController::class, 'eliminarAlumno'])->name('gestion.eliminarAlumno');
        Route::get('alumno/correos/obtener', [App\Http\Controllers\ApiController::class, 'obtenerCorreos'])->name('gestion.obtenerCorreos');
        Route::post('tutor/asignar', [App\Http\Controllers\ApiController::class, 'asignarTutor'])->name('gestion.asignarTutor');
        Route::get('alumno/tutores/obtener', [App\Http\Controllers\ApiController::class, 'obtenerTutores'])->name('gestion.obtenerTutores');
    });
});
