<?php

namespace App\DataTables;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TasksDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function($query) {
                $request = request();
                if ($request->has('search.value')) {
                    $user = Auth::user();
                    $query->where(function($query) use($user) {
                        return $query->where('user_id',$user->id)->orWhere('assignee_id',$user->id);
                    });
                    $query->where(function($childQuery) {
                        return $childQuery->whereHas('user', function($user) {
                            return $user->where('name','like','%'.request('search.value').'%');
                        })->orWhereHas('assignee', function($assignee) {
                            return $assignee->where('name','like','%'.request('search.value').'%');
                        });
                    });
                }
            })
            ->addColumn('action', 'tasks.components.action')
            ->editColumn('due_dates', function($data) {
                return $data->due_dates->format('Y-m-d');
            })
            ->addColumn('creator', function($data) {
                return $data->user->name;
            })
            ->addColumn('assignee', function($data) {
                return $data->assignee->name;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Task $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Task $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('tasks-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)->buttons(
                        Button::make('create')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            // Column::make('id'),
            Column::make('title'),
            Column::make('description'),
            Column::computed('creator'),
            Column::computed('assignee'),
            Column::make('due_dates'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Tasks_' . date('YmdHis');
    }
}
