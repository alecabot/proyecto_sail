<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParteExport implements FromCollection, withHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }
    public function headings(): array
    {
        return [
            'parte_id',
            'FechaIncidencia',
            'colectivo',
            'Profesor',
            'TramoHorario',
            'AlumnoImplicados',
            'Incidencia',
            'ConductaNegativa',
            'CorreccionAplicada',
            'Puntos',
            'DescripcionDetallada'
        ];
    }
}
