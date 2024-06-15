<?php

use App\Http\Controllers\PartesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/resumen', [PartesController::class, 'resumen'])->name('parte.resumen');
    Route::post('/upload', [PartesController::class, 'upload']);
    Route::get('/cursos', [PartesController::class, 'getCursos']);
    Route::get('/unidades', [PartesController::class, 'getUnidades']);
    Route::get('/alumnos', [PartesController::class, 'getAlumnos']);
    Route::get('/users', [PartesController::class, 'index'])->name('users.index');
    Route::post('/createParte', [PartesController::class, 'crearParte'])->name('parte.create');
    Route::get('/descargarPartePDF/{id}', [PartesController::class, 'descargarPartePDF'])->name('descargarPartePDF');
    Route::post('/updateParte/{id}', [PartesController::class, 'editarParte'])->name('parte.update');
    Route::get('/deleteParte/{id}', [PartesController::class, 'eliminarParte'])->name('parte.delete');
    Route::get('/correo', [PartesController::class, 'correo'])->name('user.correo');
    Route::get('/informe', [PartesController::class, 'informe'])->name('parte.informe');
    Route::post('/informeExcel', [PartesController::class, 'informeExcel'])->name('parte.informeExcel');
    Route::post('/importInforme', [PartesController::class, 'importInforme'])->name('parte.importInforme');


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/get-course-unit', [App\Http\Controllers\PartesController::class, 'getCourseUnit']);
    Route::get('/getParte/{id}', [App\Http\Controllers\PartesController::class, 'getParte']);
    Route::get('/getProfesores', [App\Http\Controllers\PartesController::class, 'getProfesores']);

    Route::get('/offline', function () {
        return view('vendor.laravelpwa.offline');
    })->name('offline');

    Route::group(['middleware' => ['role:jefatura']], function () {
        Route::get('/gestion/incidencias', [App\Http\Controllers\IncidenciasController::class, 'index'])->name('gestion.incidencias');
        Route::get('/gestion/incidencias/deshabilitadas', [App\Http\Controllers\IncidenciasController::class, 'deshabilitadas'])->name('gestion.incidencias.deshabilitadas');
        Route::post('/gestion/incidencias', [App\Http\Controllers\IncidenciasController::class, 'crear'])->name('gestion.incidencias.crear');
        Route::get('/gestion/incidencias/editar/{id}', [App\Http\Controllers\IncidenciasController::class, 'editar'])->name('gestion.incidencias.editar');
        Route::get('/gestion/incidencias/habilitar/{id}', [App\Http\Controllers\IncidenciasController::class, 'habilitar'])->name('gestion.incidencias.habilitar');
        Route::get('/gestion/incidencias/eliminar/{id}', [App\Http\Controllers\IncidenciasController::class, 'eliminar'])->name('gestion.incidencias.eliminar');

        Route::get('/gestion/correccionesaplicadas', [App\Http\Controllers\CorreccionesAplicadasController::class, 'index'])->name('gestion.correcciones');
        Route::get('/gestion/correccionesaplicadas/deshabilitadas', [App\Http\Controllers\CorreccionesAplicadasController::class, 'deshabilitadas'])->name('gestion.correcciones.deshabilitadas');
        Route::post('/gestion/correccionesaplicadas', [App\Http\Controllers\CorreccionesAplicadasController::class, 'crear'])->name('gestion.correcciones.crear');
        Route::get('/gestion/correccionesaplicadas/editar/{id}', [App\Http\Controllers\CorreccionesAplicadasController::class, 'editar'])->name('gestion.correcciones.editar');
        Route::get('/gestion/correccionesaplicadas/habilitar/{id}', [App\Http\Controllers\CorreccionesAplicadasController::class, 'habilitar'])->name('gestion.correcciones.habilitar');
        Route::get('/gestion/correccionesaplicadas/eliminar/{id}', [App\Http\Controllers\CorreccionesAplicadasController::class, 'eliminar'])->name('gestion.correcciones.eliminar');

        Route::get('/gestion/conductasnegativas', [App\Http\Controllers\ConductasNegativasController::class, 'index'])->name('gestion.negativas');
        Route::get('/gestion/conductasnegativas/deshabilitadas', [App\Http\Controllers\ConductasNegativasController::class, 'deshabilitadas'])->name('gestion.negativas.deshabilitadas');
        Route::post('/gestion/conductasnegativas', [App\Http\Controllers\ConductasNegativasController::class, 'crear'])->name('gestion.negativas.crear');
        Route::get('/gestion/conductasnegativas/editar/{id}', [App\Http\Controllers\ConductasNegativasController::class, 'editar'])->name('gestion.negativas.editar');
        Route::get('/gestion/conductasnegativas/habilitar/{id}', [App\Http\Controllers\ConductasNegativasController::class, 'habilitar'])->name('gestion.negativas.habilitar');
        Route::get('/gestion/conductasnegativas/eliminar/{id}', [App\Http\Controllers\ConductasNegativasController::class, 'eliminar'])->name('gestion.negativas.eliminar');

        Route::get('/gestion/profesoralumno', [App\Http\Controllers\ProfesorAlumnoController::class, 'index'])->name('gestion.profesoralumno');
        Route::get('/gestion/profesoralumno/habilitar/{dni}', [App\Http\Controllers\ProfesorAlumnoController::class, 'habilitar'])->name('gestion.profesoralumno.habilitar');
        Route::get('/gestion/profesoralumno/profesor/eliminar/{dni}', [App\Http\Controllers\ProfesorAlumnoController::class, 'eliminarProfesor'])->name('gestion.profesoralumno.profesor.eliminar');
        Route::post('/gestion/profesoralumno/profesor/crear', [App\Http\Controllers\ProfesorAlumnoController::class, 'crearProfesor'])->name('gestion.profesoralumno.profesor.crear');
        Route::get('/gestion/profesoralumno/profesor/deshabilitados', [App\Http\Controllers\ProfesorAlumnoController::class, 'deshabilitados'])->name('gestion.profesoralumno.profesor.deshabilitados');
        Route::get('/gestion/profesoralumno/profesor/editar', [App\Http\Controllers\ProfesorAlumnoController::class, 'editarProfesor'])->name('gestion.profesoralumno.profesor.editar');

        Route::get('/gestion/tutores', [App\Http\Controllers\TutoresController::class, 'index'])->name('gestion.tutores');

        Route::get('/import', [PartesController::class, 'cargarImport'])->name('users.import');
        Route::post('/import', [PartesController::class, 'import'])->name('users.import');

        // Rutas para la gestión de los puntos
        Route::get('/gestion/puntos', function () {
            return view("gestion.puntos");
        })->name('gestion.puntos');
        Route::get('/gestion/profesoralumno/reiniciarpuntos', [App\Http\Controllers\ProfesorAlumnoController::class, 'reinciarPuntos'])->name('gestion.reinciarpuntos');
    });
});
