<?php

namespace App\Http\Controllers;

use App\DataTables\ResumenParteDataTable;

use App\Exports\ParteExport;
use App\Imports\AlumnosImport;
use App\DataTables\ParteDataTable;
use App\Imports\ParteImport;
use App\Mail\CorreoJefaturaParte;
use App\Mail\CorreoPuntosParte;
use App\Mail\CorreoTutores;
use App\Mail\CorreoTutoresCurso;
use App\Models\Alumno;
use App\Models\AlumnoParte;
use App\Models\AnioAcademico;
use App\Models\Conductanegativa;
use App\Models\Correccionaplicada;
use App\Models\Correo;
use App\Models\Curso;
use App\Models\Incidencia;
use App\Models\Parte;
use App\Models\ParteConductanegativa;
use App\Models\ParteCorreccionsaplicada;
use App\Models\ParteIncidencia;
use App\Models\Profesor;
use App\Models\Tramohorario;
use App\Models\Unidad;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PartesController extends Controller
{

    public function index(ParteDataTable $dataTable)
    {
        $anoAcademico = AnioAcademico::all();
        $profesores = Profesor::all()->where('habilitado', '=', true);

        $tramos = Tramohorario::all();

        $cursos = Curso::all();

        $incidencias = Incidencia::all()->where('habilitado', '=', true);

        $conductasNegativas = Conductanegativa::all()->where('habilitado', '=', true);

        $correcionesAplicadas = Correccionaplicada::all()->where('habilitado', '=', true);
        return $dataTable->render('users.index', ['anoAcademico' => $anoAcademico, 'profesores' => $profesores, 'tramos' => $tramos, 'cursos' => $cursos, 'incidencias' => $incidencias, 'conductasNegativas' => $conductasNegativas, 'correcionesAplicadas' => $correcionesAplicadas]);
    }

    public function getCourseUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Curso' => ['required', 'numeric', 'between:1,5000'],
            'Unidad' => ['required', 'numeric', 'between:1,5000'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Obtener los valores de los parámetros curso y unidad de la solicitud
        $curso = $request->query('curso');
        $unidad = $request->query('unidad');

        // Lógica adicional para procesar los valores de curso y unidad si es necesario
        // Por ejemplo, puedes obtener información adicional de la base de datos aquí

        return response()->json(['curso' => $curso, 'unidad' => $unidad]);
    }


    public function resumen(ResumenParteDataTable $dataTable)
    {
        $anoAcademico = AnioAcademico::all();
        return $dataTable->render('users.resumen', ['anoAcademico' => $anoAcademico]);
    }

    public function informe()
    {
        $anoAcademico = AnioAcademico::all();
        return view('parte.informe', ['anoAcademico' => $anoAcademico]);
    }

    public function informeExcel(Request $request)
    {
        // Obtén los valores seleccionados
        // Obtén los valores seleccionados
        $anoAcademico = $request->input('anoAcademico');
        $curso = $request->input('curso');
        $unidad = $request->input('unidad');

        // Realiza la consulta
        $query = Parte::
        leftJoin('alumno_partes', 'partes.id', '=', 'alumno_partes.parte_id')
            ->leftJoin('alumnos', 'alumno_partes.alumno_dni', '=', 'alumnos.dni')
            ->leftJoin('unidades', 'alumnos.id_unidad', '=', 'unidades.id')
            ->leftJoin('cursos', 'unidades.id_curso', '=', 'cursos.id')
            ->leftJoin('profesors', 'partes.profesor_dni', '=', 'profesors.dni')
            ->leftJoin('incidencias', 'partes.incidencia_id', '=', 'incidencias.id')
            ->leftJoin('parte_conductanegativas', 'partes.id', '=', 'parte_conductanegativas.parte_id')
            ->leftJoin('tramohorarios', 'partes.tramo_horario_id', '=', 'tramohorarios.id')
            ->leftJoin('conductanegativas', 'parte_conductanegativas.conductanegativas_id', '=', 'conductanegativas.id')
            ->leftJoin('correccionaplicadas', 'partes.correccionaplicadas_id', '=', 'correccionaplicadas.id')
            ->when($anoAcademico, function ($query, $anoAcademico) {
                return $query->where('cursos.id_anio_academico', $anoAcademico);
            })
            ->when($curso, function ($query, $curso) {
                return $query->where('unidades.id_curso', $curso);
            })
            ->when($unidad, function ($query, $unidad) {
                return $query->where('alumnos.id_unidad', $unidad);
            })
            ->select(
                'partes.id',
                'partes.created_at as fecha_incidencia',
                'partes.colectivo as colectivo',
                'profesors.nombre as profesor',
                'tramohorarios.nombre as tramo_horario',
                'alumnos.nombre as alumno_implicado',
                'incidencias.descripcion as incidencia',
                DB::raw('GROUP_CONCAT(DISTINCT conductanegativas.descripcion SEPARATOR ", ") as conducta_negativa'),
                'correccionaplicadas.descripcion as correccion_aplicada',
                'partes.puntos_penalizados as puntos',
                'partes.descripcion_detallada as descripcion_detallada',

            )

            ->groupBy('partes.id');




        // Exporta los datos a un archivo Excel
        return Excel::download(new ParteExport($query), 'Some_Report.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);


    }

    public function importInforme(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ParteImport, $request->file('file'));

        return redirect()->route('users.index')
            ->with('success', 'Datos importados correctamente.');
    }

    public function crearParte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Profesor' => 'required|in:' . implode(',', Profesor::all()->pluck('dni')->toArray()),
            'TramoHorario' => 'required|in:' . implode(',', Tramohorario::all()->pluck('id')->toArray()),
            'Alumno' => 'required',
            'Puntos' => 'required|numeric',
            'Fecha' => 'required|date',
            'Incidencia' => 'required|in:' . implode(',', Incidencia::all()->pluck('id')->toArray()),
            'ConductasNegativa' => 'required',
            'CorrecionesAplicadas' => 'required|in:' . implode(',', Correccionaplicada::all()->pluck('id')->toArray()),
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach (request('Alumno') as $alumno) {
            if (!($this->validarDNI($alumno))) {
                return response()->json([
                    'success' => false,
                    'errors' => ["- DNI con formato incorrecto."]
                ], 422);
            }
        }

        foreach (request('ConductasNegativa') as $conducta) {
            if (!is_numeric($conducta) || $conducta < 1 || $conducta > 5000) {
                return response()->json([
                    'success' => false,
                    'errors' => ["- Problema al obtener los datos de conductas."]
                ], 422);
            }
        }

        $fechaInput = request('Fecha');


        $fecha = Carbon::parse($fechaInput)->format('Y-m-d H:i');
        $parte = Parte::create([
            'profesor_dni' => request('Profesor'),
            'tramo_horario_id' => request('TramoHorario'),
            'colectivo' => count(request('Alumno')) > 1 ? 'Si' : 'No',
            'correccionaplicadas_id' => request('CorrecionesAplicadas'),
            'incidencia_id' => request('Incidencia'),
            'created_at' => $fecha,
            'puntos_penalizados' => intval(request('Puntos')),
            'descripcion_detallada' => request('DescripcionDetallada'),
        ]);

        foreach (request('ConductasNegativa') as $conducta) {
            ParteConductanegativa::create([
                'parte_id' => $parte->id,
                'conductanegativas_id' => $conducta,
            ]);
        }


        foreach (request('Alumno') as $alumno) {


            AlumnoParte::create([
                'alumno_dni' => $alumno,
                'parte_id' => $parte->id,
            ]);


            $alumnoModel = Alumno::where('dni', $alumno)->first();
            $puntosARestar = intval(request('Puntos'));

            if ($alumnoModel->puntos <= $puntosARestar) {
                $alumnoModel->puntos = 0;
                foreach ($alumnoModel->correos as $correo) {
//                Mail::to($correo->correo)->queue(new CorreoPuntosParte($alumnoModel));
                    Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoPuntosParte($alumnoModel));
                }
            } else {
                $alumnoModel->decrement('puntos', $puntosARestar);
            }

            $alumnoModel->save();
            foreach ($alumnoModel->correos as $correo) {
//                Mail::to($correo->correo)->queue(new CorreoTutoresCurso($alumnoModel, $parte));
                Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutores($alumnoModel, $parte));
            }

        }
        Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoJefaturaParte($parte));
        $mailTutor = $parte->alumnos->first()->unidad->profesor;
        if ($mailTutor != null) Mail::to($mailTutor->correo)->queue(new CorreoTutoresCurso($parte));
//        Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutoresCurso($parte));

        return redirect()->route('users.index')
            ->with('success', 'Parte creado correctamente.');
    }


    public function editarParte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Profesor' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'TramoHorario' => ['required', 'numeric', 'between:1,5000'],
            'Alumno' => 'required',
            'Puntos' => ['required', 'numeric', 'between:0,50'],
            'Fecha' => ['required', 'date'],
            'Incidencia' => ['required', 'numeric', 'between:1,5000'],
            'ConductasNegativa' => 'required',
            'CorrecionesAplicadas' => ['required', 'numeric', 'between:1,5000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach (request('Alumno') as $alumno) {
            if (!($this->validarDNI($alumno))) {
                return response()->json([
                    'success' => false,
                    'errors' => ["- DNI con formato incorrecto."]
                ], 422);
            }
        }

        foreach (request('ConductasNegativa') as $conducta) {
            if (!is_numeric($conducta) || $conducta < 1 || $conducta > 5000) {
                return response()->json([
                    'success' => false,
                    'errors' => ["- Problema al obtener los datos de conductas."]
                ], 422);
            }
        }

        // Obtén el parte basado en el id
        $parte = Parte::find($request->input('id'));

        // Obtén la lista actual de alumnos asociados con el parte
        $alumnosActuales = $parte->alumnos->pluck('dni')->toArray();

        // Obtén la lista de alumnos seleccionados en el select
        $alumnosSeleccionados = $request->input('Alumno');

        // Encuentra los alumnos que necesitan ser eliminados
        $alumnosAEliminar = array_diff($alumnosActuales, $alumnosSeleccionados);

        // Elimina las relaciones para los alumnos que necesitan ser eliminados
        foreach ($alumnosAEliminar as $dniAlumno) {
            $alumno = Alumno::where('dni', $dniAlumno)->first();
            if ($alumno) {
                $parte->alumnos()->detach($alumno->dni);
                $parte->alumnos()->increment('puntos', $parte->puntos_penalizados);
                foreach ($alumno->correos as $correo) {
//                Mail::to($correo->correo)->queue(new CorreoTutores($alumnoModel, $parte));
                    Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutores($alumno, $parte, true));
                }

            }


        }

        $fechaInput = request('Fecha');


        $fecha = Carbon::parse($fechaInput)->format('Y-m-d H:i');

        // Actualiza los demás campos del parte
        $parte->update([
            'created_at' => $fecha,
            'colectivo' => count(request('Alumno')) > 1 ? 'Si' : 'No',
            'profesor_dni' => $request->input('Profesor'),
            'tramo_horario_id' => $request->input('TramoHorario'),
            'puntos_penalizados' => $request->input('Puntos'),
            'descripcion_detallada' => $request->input('DescripcionDetallada'),
            'correccionaplicadas_id' => $request->input('CorrecionesAplicadas'),
            'incidencia_id' => $request->input('Incidencia'),
        ]);


        // Obtén las conductas negativas actuales asociadas con el parte
        $conductasActuales = ParteConductanegativa::where('parte_id', $parte->id)->pluck('conductanegativas_id')->toArray();

        // Obtén las conductas negativas seleccionadas en la solicitud
        $conductasSeleccionadas = $request->input('ConductasNegativa');

        // Encuentra las conductas que necesitan ser eliminadas
        $conductasAEliminar = array_diff($conductasActuales, $conductasSeleccionadas);

        // Elimina las relaciones para las conductas que necesitan ser eliminadas
        foreach ($conductasAEliminar as $conductaId) {
            $conducta = Conductanegativa::where('id', $conductaId)->first();
            if ($conducta) {
                $parte->conductanegativas()->detach($conducta->id);
            }
        }

        // Encuentra las conductas que necesitan ser añadidas
        $conductasAAñadir = array_diff($conductasSeleccionadas, $conductasActuales);

        // Añade las nuevas relaciones para las conductas que necesitan ser añadidas
        foreach ($conductasAAñadir as $conductaId) {
            ParteConductanegativa::create([
                'parte_id' => $parte->id,
                'conductanegativas_id' => $conductaId,
            ]);
        }

        // Actualiza las relaciones para los alumnos seleccionados


        $alumnosAAñadir = array_diff($alumnosSeleccionados, $alumnosActuales);

        foreach (request('Alumno') as $alumno) {

            if (in_array($alumno, $alumnosAAñadir)) {
                AlumnoParte::create([
                    'alumno_dni' => $alumno,
                    'parte_id' => $parte->id,
                ]);
                $alumnoModel = Alumno::where('dni', $alumno)->first();
                $puntosARestar = intval(request('Puntos'));

                $alumnoModel->increment('puntos', $parte->puntos_penalizados);
                if ($alumnoModel->puntos <= $puntosARestar) {
                    $alumnoModel->puntos = 0;
                    // Jefatura
                    Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoPuntosParte($alumnoModel));
                    // Tutor
                    Mail::to($alumnoModel->unidad->profesor->correo)->queue(new CorreoPuntosParte($alumnoModel));
                } else {
                    $alumnoModel->decrement('puntos', $puntosARestar);
                }

                $alumnoModel->save();
                foreach ($alumno->correos as $correo) {
//                Mail::to($correo->correo)->queue(new CorreoTutoresCurso($alumnoModel, $parte));
                    Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutores($alumno, $parte));
                }
            } else {

                $alumnoModel = Alumno::where('dni', $alumno)->first();
                $puntosARestar = intval(request('Puntos'));

                $alumnoModel->increment('puntos', $parte->puntos_penalizados);
                if ($alumnoModel->puntos <= $puntosARestar) {
                    $alumnoModel->puntos = 0;
                    //Jefatura
                    Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoPuntosParte($alumnoModel));
                    // Tutor de curso
                    Mail::to($alumnoModel->unidad->profesor->correo)->queue(new CorreoPuntosParte($alumnoModel));
                } else {
                    $alumnoModel->decrement('puntos', $puntosARestar);
                }

                $alumnoModel->save();

                foreach ($alumnoModel->correos as $correo) {
//                Mail::to($correo->correo)->queue(new CorreoTutoresCurso($alumnoModel, $parte));
                    Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutores($alumnoModel, $parte, false, true));
                }
            }


        }


        Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoJefaturaParte($parte, false, true));
        $mailTutor = $parte->alumnos->first()->unidad->profesor;
        if ($mailTutor != null) Mail::to($mailTutor->correo)->queue(new CorreoTutoresCurso($parte,false,true));
//        Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutoresCurso($parte,false,true));
        return redirect()->route('users.index')
            ->with('success', 'Parte creado correctamente.');
    }

    public function eliminarParte($id)
    {
        if (!is_numeric($id) || $id < 1 || $id > 5000) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos del parte."]
            ], 422);
        }

        $parte = Parte::find($id);

        $alumnos = AlumnoParte::where('parte_id', $parte->id)->get();

        foreach ($alumnos as $alumno) {
            $alumnoModel = Alumno::where('dni', $alumno->alumno_dni)->first();
            $alumnoModel->increment('puntos', $parte->puntos_penalizados);
            $alumnoModel->save();
            foreach ($alumnoModel->correos as $correo) {
//                Mail::to($correo->correo)->queue(new CorreoTutoresCurso($alumnoModel, $parte));
                Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoTutores($alumnoModel, $parte, true));
            }

        }
        Mail::to('alejandrocbt@hotmail.com')->queue(new CorreoJefaturaParte($parte, true));
        $mailTutor = $parte->alumnos->first()->unidad->profesor;
        if ($mailTutor != null) Mail::to($mailTutor->correo)->queue(new CorreoTutoresCurso($parte,true));
        $parte->delete();

        return redirect()->route('users.index')
            ->with('success', 'Parte eliminado correctamente.');
    }

    public function getParte($id)
    {
        if (!is_numeric($id) || $id < 1 || $id > 5000) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos del parte."]
            ], 422);
        }
        $parteId = $id;
        $parte = Parte::find($parteId);
        $profesorAll = Profesor::all();
        //$profesorAll->push($parte->profesors);
        $alumnos = AlumnoParte::where('parte_id', $parteId)->get();
        //$profesor = Profesor::where('dni', $parte->profesor_dni)->first()->get();
        $conductasNegativas = ParteConductanegativa::where('parte_id', $parteId)->get();

        return response()->json([
            'id' => $parte->id,
            'fecha' => Carbon::parse($parte->created_at)->format('Y-m-d H:i'), // Formato 'Y-m-d' para que funcione con el componente 'date' de Vue
            'alumnos' => $alumnos,
            'profesor' => $parte->profesor_dni,
            'profesorAll' => $profesorAll,
            'incidencia' => $parte->incidencias->first()->id,
            'conductasNegativas' => $conductasNegativas,
            'correcionesAplicadas' => $parte->correccionesaplicadas->first()->id,
            'tramoHorario' => $parte->tramo_horario_id,
            'puntos' => $parte->puntos_penalizados,
            'descripcionDetallada' => $parte->descripcion_detallada,

        ]);
    }

    function getProfesores()
    {
        $profesoresAll = Profesor::all()->where('habilitado', '=', true);
        return response()->json([
            'profesoresAll' => $profesoresAll

        ]);

    }

    public function getCursos(Request $request)
    {
        $anoId = $request->selectedId;
        if (!is_numeric($anoId) || $anoId < 1 || $anoId > 5000) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos del parte."]
            ], 422);
        }
        $cursos = Curso::where('id_anio_academico', $anoId)->get();

        $cursoData = [];
        foreach ($cursos as $curso) {
            $cursoData[$curso->id] = $curso->nombre;
        }

        return response()->json($cursoData);
    }

    public function descargarPartePDF($id)
    {
        if (!is_numeric($id) || $id < 1 || $id > 5000) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos del parte."]
            ], 422);
        }
        // Obtén el parte basado en el ID
        $parte = Parte::find($id);

        // Comprueba si la descripción detallada está vacía
        if (!empty($parte->descripcion_detallada)) {
            // Crea una nueva instancia de DOMDocument
            $dom = new \DOMDocument();

            // Carga el contenido HTML en DOMDocument
            @$dom->loadHTML($parte->descripcion_detallada, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            // Encuentra todas las imágenes en el contenido HTML
            $images = $dom->getElementsByTagName('img');

            // Itera sobre todas las imágenes
            foreach ($images as $image) {
                // Obtén la ruta de la imagen
                $src = $image->getAttribute('src');

                // Si la ruta de la imagen es relativa, conviértela en una ruta absoluta
                if (!filter_var($src, FILTER_VALIDATE_URL)) {
                    $absolutePath = public_path($src);

                    // Comprueba si el archivo existe antes de intentar obtener su contenido
                    if (file_exists($absolutePath)) {
                        // Codifica la imagen en base64
                        $type = pathinfo($absolutePath, PATHINFO_EXTENSION);
                        $data = file_get_contents($absolutePath);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                        // Reemplaza la ruta de la imagen con la cadena base64
                        $image->setAttribute('src', $base64);
                    }

                    // Agrega la clase CSS a la imagen
                    $image->setAttribute('class', 'responsive-image');
                }
            }

            // Guarda el contenido HTML con las rutas de las imágenes actualizadas
            $parte->descripcion_detallada = $dom->saveHTML();
        }

        $pdf = PDF::loadView('users.partePDF', ['parte' => $parte]);

        // Devuelve el PDF como una respuesta
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="parte.pdf"',
        ]);
    }


    public function getUnidades(Request $request)
    {
        $cursoId = $request->selectedId;
        if (!is_numeric($cursoId) || $cursoId < 1 || $cursoId > 5000) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos del curso."]
            ], 422);
        }
        $unidades = Unidad::where('id_curso', $cursoId)->get();

        $unidadData = [];
        foreach ($unidades as $unidad) {
            $unidadData[$unidad->id] = $unidad->nombre;
        }

        return response()->json($unidadData);
    }

    public function getAlumnos(Request $request)
    {
        $unidadId = $request->selectedId;
        if (!is_numeric($unidadId) || $unidadId < 1 || $unidadId > 5000) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos de la unidad."]
            ], 422);
        }
        $alumnos = Alumno::where('id_unidad', $unidadId)->get();

        $alumnoData = [];
        foreach ($alumnos as $alumno) {
            $alumnoData[$alumno->dni] = $alumno->nombre;
        }

        return response()->json($alumnoData);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|mimes:xlsx,xls|max:3000000'
        ]);
        $file = $request->file('upload');
        $fileName = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move(public_path('uploads'), $fileName);

        return response()->json([
            'uploaded' => 1,
            'fileName' => $fileName,
            'url' => '/uploads/' . $fileName
        ]);
    }


    public function correo()
    {
        return view('users.correo');
    }

    public function import(Request $request)
    {
        // Comprueba si se ha pasado un archivo
        if (!$request->hasFile('import_file')) {
            return redirect()->route('users.import')
                ->with('error', 'No se ha subido ningún archivo.');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Correo::truncate();
        Alumno::truncate();
        Curso::truncate();
        Unidad::truncate();
        AnioAcademico::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = $request->file('import_file');

        Excel::import(new AlumnosImport, $file);

        return redirect()->route('users.import')
            ->with('Satisfactorio', 'Datos importados correctamente.');
    }

    public function cargarImport()
    {
        return view('users.import-excel');
    }

    // Función importada de https://robertostory.com/blog/12/validacion-de-dni-nie-espanol-php
    public function validarDNI($value)
    {
        $pattern = "/^[XYZ]?\d{5,8}[A-Z]$/";
        $dni = strtoupper($value);
        if(preg_match($pattern, $dni))
        {
            $number = substr($dni, 0, -1);
            $number = str_replace('X', 0, $number);
            $number = str_replace('Y', 1, $number);
            $number = str_replace('Z', 2, $number);
            $dni = substr($dni, -1, 1);
            $start = $number % 23;
            $letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
            $letter = substr('TRWAGMYFPDXBNJZSQVHLCKET', $start, 1);
            if($letter != $dni)
            {
                return false;
            } else {
                return true;
            }
        }else{
            return false;
        }
    }

}
