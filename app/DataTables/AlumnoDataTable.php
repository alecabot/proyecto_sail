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

class AlumnoDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('dni')
            ->rawColumns(['Correos'])
            ->filterColumn('nombred', function($query, $keyword) {
                $query->whereRaw("alumnos.nombre like ?", ["%{$keyword}%"]);
            })
            //Para que pueda buscar por los correos también
            ->filterColumn('Correos', function($query, $keyword) {
                $query->where(function($query) use ($keyword) {
                    $query->where('correos.correo', 'like', "%{$keyword}%");
                });
            });
    }

    public function query(Alumno $model): QueryBuilder
    {
        $unidad = $this->request()->get('unidad');
        $searchTerm = $this->request()->get('search')['value'] ?? null;

        $query = $model->newQuery()

        ->leftJoin('correos', 'correos.alumno_dni', '=', 'alumnos.dni')
        ->select('alumnos.*', DB::raw("CONCAT('<ul><li>', GROUP_CONCAT(CONCAT(correos.correo, ' (', correos.tipo, ')') SEPARATOR '</li><li>'), '</li></ul>') as Correos"))
        ->groupBy('alumnos.dni');


        if (!empty($unidad)) {

            $query->where('alumnos.id_unidad', '=', $unidad);
        }

        if (!empty($searchTerm)) {
            $model->query()->where('alumnos.dni', 'like', "%{$searchTerm}%")
                  ->orWhere('alumnos.nombre', 'like', "%{$searchTerm}%");
        }

        return $query;

    }

    public function html(): \Yajra\DataTables\Html\Builder
    {
        return $this->builder()
            ->setTableId('alumnos-table')
            ->columns($this->getColumns())
            ->minifiedAjax()

            ->orderBy(0)
            ->scrollX(true)
            ->language(['url' => '/js/spanish.json'])

            ->parameters([
                'pageLength' => 5, // Limitar los registros a 5 por página
                'lengthMenu' => [[5, 10, 25, 50], [5, 10, 25, 50]],
            ])
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
            Column::make('dni')->title('DNI')->data('dni')->className('align-middle text-center'),
            Column::make('nombre')->name('alumnos.nombre')->title('Nombre')->data('nombre')->className('align-middle text-center'),
            Column::make('Correos')->name('Correos')->title('Correos')->data('Correos')->className('align-middle text-center lista-datatable'),
            Column::make('puntos')->title('Puntos')->data('puntos')->type('num')
            ->className('align-middle text-center')->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Alumnos_'.date('YmdHis');
    }
}
