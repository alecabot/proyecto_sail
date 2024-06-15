<?php

namespace App\DataTables;

use App\Models\Alumno;
use App\Models\Parte;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\SearchPane;
use Yajra\DataTables\Services\DataTable;

class ResumenParteDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ;
    }

    public function query(Alumno $model): QueryBuilder
    {
        $unidad = $this->request()->get('unidad');
        $searchTerm = $this->request()->get('search')['value'] ?? null;

        if (empty($unidad)) {
            return $model->query()->whereRaw('1 = 0');
        }
        if (!empty($searchTerm)) {
            // Aquí puedes ajustar tu consulta para la búsqueda global
        }


        $query = $model->newQuery()
            ->leftJoin('alumno_partes', 'alumnos.dni', '=', 'alumno_partes.alumno_dni')
            ->leftJoin('partes', 'alumno_partes.parte_id', '=', 'partes.id')
            ->leftJoin('incidencias', 'partes.incidencia_id', '=', 'incidencias.id')
            ->leftJoin('parte_conductanegativas', 'partes.id', '=', 'parte_conductanegativas.parte_id')
            ->leftJoin('conductanegativas', 'parte_conductanegativas.conductanegativas_id', '=', 'conductanegativas.id')
            ->select(
                'alumnos.*',
                DB::raw('COUNT(DISTINCT partes.incidencia_id) as count_incidencia'),
                DB::raw('COUNT(DISTINCT parte_conductanegativas.conductanegativas_id) as count_conducta_negativa'),
                DB::raw("COUNT(CASE WHEN conductanegativas.tipo = 'grave' THEN parte_conductanegativas.conductanegativas_id END) as count_conducta_negativa_grave"),
                DB::raw("COUNT( CASE WHEN conductanegativas.tipo = 'contraria' THEN parte_conductanegativas.conductanegativas_id END) as count_conducta_negativa_contraria"),
            )
            ->groupBy('alumnos.dni');


        if (!empty($unidad)) {

            $query->where('alumnos.id_unidad', '=', $unidad);
        }

        return $query;


    }

    public function html(): \Yajra\DataTables\Html\Builder
    {

        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()


            ->scrollX(true)
            ->language(['url' => 'js/spanish.json'])

            ->parameters([

            ])
//
            ->buttons([
                Button::make('excel')->titleAttr('Exportar a Excel'),
                Button::make('print')->titleAttr('Imprimir'),
                Button::make('reset')->titleAttr('Restablecer'),
                Button::make('reload')->titleAttr('recargar'),
            ]);

    }

    public function getColumns(): array
    {
        return [
            Column::make('nombre')->name('alumnos.nombre')->title('Nombre')->className('align-middle text-center'),
            Column::make('count_incidencia')->name('count_incidencia')->title('Incidencias')->className('align-middle text-center'),
            Column::make('count_conducta_negativa_grave')->name('count_conducta_negativa_grave')->title('Conductas grave')->className('align-middle text-center'),
            Column::make('count_conducta_negativa_contraria')->name('count_conducta_negativa_contraria')->title('Conductas contrarias')->className('align-middle text-center'),
            Column::make('puntos')->name('alumnos.puntos')->title('Puntos restantes')->className('align-middle text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ResumenPartes_'.date('YmdHis');
    }
}
