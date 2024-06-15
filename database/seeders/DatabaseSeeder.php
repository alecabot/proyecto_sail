<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Alumno;
use App\Models\AlumnoParte;
use App\Models\AnioAcademico;
use App\Models\Correo;
use App\Models\Parte;
use App\Models\ParteConductanegativa;
use App\Models\ParteCorreccionsaplicada;
use App\Models\ParteIncidencia;
use App\Models\Profesor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'jefatura']);
        Role::create(['name' => 'profesor']);

        AnioAcademico::factory()->create(['anio_academico' => '2023-2024']);

        $tramosHorarios = [
            ["nombre" => "En clase"],
            ["nombre" => "En el intercambio de clases"],
            ["nombre" => "Entrada/Salida"],
            ["nombre" => "Recreo"],
            ["nombre" => "Fuera del centro"],
            ["nombre" => "En el comedor"],
            ["nombre" => "En el aula matinal"],
            ["nombre" => "Durante las actividades extraescolares"],
            ["nombre" => "Otros"],
        ];

        foreach ($tramosHorarios as $tramoHorario) \App\Models\Tramohorario::factory()->create($tramoHorario);

        $incidentes = [
            ["descripcion" => "Jugar en clase", 'habilitado' => true],
            ["descripcion" => "Pelea con un compañero", 'habilitado' => true],
            ["descripcion" => "Malos modos", 'habilitado' => true],
            ["descripcion" => "Jugar en clase", 'habilitado' => true],
            ["descripcion" => "Uso del móvil sin permiso", 'habilitado' => true],
            ["descripcion" => "Uso indebido del PC", 'habilitado' => true],
            ["descripcion" => "Uso del PC", 'habilitado' => false],
        ];

        foreach ($incidentes as $incidente) \App\Models\Incidencia::factory()->create($incidente);

        $conductasNegativas = [
            ["descripcion" => "Perturbación del normal desarrollo de las actividades de la clase", "tipo" => "Contraria", 'habilitado' => true],
            ["descripcion" => "Agresión física a un miembro de la comunidad educativa.", "tipo" => "Grave", 'habilitado' => true],
            ["descripcion" => "Falta de colaboración sistemática en la realización de las actividades.", "tipo" => "Contraria", 'habilitado' => true],
            ["descripcion" => "Impedir o dificultar el estudio a sus compañeros.", "tipo" => "Contraria", 'habilitado' => true],
            ["descripcion" => "Actuaciones incorrectas hacia algún miembro de la comunidad educativa.", "tipo" => "Contraria", 'habilitado' => true],
            ["descripcion" => "Reiteración en un mismo curso de conductas contrarias a las normas de convivencia.", "tipo" => "Grave", 'habilitado' => true],
            ["descripcion" => "No asistencia a una actividad en la que se había comprometido.", "tipo" => "Grave", 'habilitado' => false],
        ];
        foreach ($conductasNegativas as $conductaNegativa) \App\Models\Conductanegativa::factory()->create($conductaNegativa);

        $correcionesAplicadas = [
            ["descripcion" => "Ninguna.", 'habilitado' => true],
            ["descripcion" => "Suspender el derecho de asistencia al centro entre 1 y 3 días.", 'habilitado' => true],
            ["descripcion" => "Suspender el derecho de asistencia al centro entre 4 y 30 días.", 'habilitado' => true],
            ["descripcion" => "Realizar tareas fuera del horario lectivo del Centro.", 'habilitado' => true],
            ["descripcion" => "Permanecimiento en clase durante el tiempo de recreo", 'habilitado' => false],
        ];
        foreach ($correcionesAplicadas as $correcionAplicada) \App\Models\Correccionaplicada::factory()->create($correcionAplicada);
        
        $cursos = [
            ['nombre' => '1º de ESO','id_anio_academico' => 1],
            ['nombre' => '2º de ESO','id_anio_academico' => 1],
            ['nombre' => '3º de ESO','id_anio_academico' => 1],
            ['nombre' => '4º de ESO','id_anio_academico' => 1],
            ['nombre' => '1º de Bachillerato','id_anio_academico' => 1],
            ['nombre' => '2º de Bachillerato','id_anio_academico' => 1],
        ];

        foreach ($cursos as $curso) \App\Models\Curso::factory()->create($curso);

        $unidades = [
            ['id_curso' => 1, 'nombre' => '1º de ESO A'],
            ['id_curso' => 1, 'nombre' => '1º de ESO B'],
            ['id_curso' => 2, 'nombre' => '2º de ESO A'],
            ['id_curso' => 2, 'nombre' => '2º de ESO B'],
            ['id_curso' => 3, 'nombre' => '3º de ESO A'],
            ['id_curso' => 3, 'nombre' => '3º de ESO B'],
            ['id_curso' => 4, 'nombre' => '4º de ESO A'],
            ['id_curso' => 4, 'nombre' => '4º de ESO B'],
            ['id_curso' => 5, 'nombre' => '1º de Bachillerato A'],
            ['id_curso' => 5, 'nombre' => '1º de Bachillerato B'],
            ['id_curso' => 6, 'nombre' => '2º de Bachillerato A'],
            ['id_curso' => 6, 'nombre' => '2º de Bachillerato B'],

        ];

        foreach ($unidades as $unidad) \App\Models\Unidad::factory()->create($unidad);

        

        $jefatura = User::factory()->create([
            'name' => 'jefatura',
            'email' => 'jefatura',
            'password' => Hash::make('je21700010'),
        ]);

        $profesor = User::factory()->create([
            'name' => 'profesor',
            'email' => 'profesor',
            'password' => Hash::make('21700010'),
        ]);

        $jefatura->assignRole('jefatura');
        $profesor->assignRole('profesor');
        
        foreach (range(0, 20) as $i) {

            Alumno::factory()->create();
            foreach (range(0, 2) as $o) {
                Correo::factory()->create();
            }
            Profesor::factory()->create();
            Parte::factory()->create();
            foreach (range(0, 1) as $o) {
                ParteConductanegativa::factory()->create();
            }
        }

        foreach (range(0, 20) as $i) {
            AlumnoParte::factory()->create();
        }
        
    }
}
