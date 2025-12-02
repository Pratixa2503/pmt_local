<?php

namespace App\DataTables;

use App\Models\SkillMaster;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class SkillMasterDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($type) {
                $encrypted = Crypt::encryptString($type->id);
                $actions = '';
                if (auth()->user()->can('edit skill master')) {
                    $actions .= '<a href="' . route('skill-masters.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete skill master')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-skill-master" data-id="' . $encrypted . '" class="delete-skill-master"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(SkillMaster $model)
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('skill-master-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'name',                  'name' => 'name',                  'title' => 'Name'],
            ['data' => 'skill_expertise_level','name' => 'skill_expertise_level','title' => 'Skill Expertise Level'],
            ['data' => 'ctc',                   'name' => 'ctc',                   'title' => 'CTC'],
            ['data' => 'average_handling_time','name' => 'average_handling_time','title' => 'Avg. Handling Time'],
            ['data' => 'actions',               'name' => 'actions',               'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
