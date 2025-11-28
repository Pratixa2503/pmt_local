<?php
namespace App\Support;

use App\Services\MasterDataResolver;

class MasterData
{
    public static function getProjectMasterData(): array
    {
        $r = new MasterDataResolver();

        return [
            'project_types'          => $r->list('project_types'),
            'departments'            => $r->list('departments'),
            'project_priorities'     => $r->list('project_priorities'),
            'project_statuses'       => $r->list('project_statuses'),
            'frequencies_of_delivery'=> $r->list('frequencies_of_delivery'),
            'modes_of_delivery'      => $r->list('modes_of_delivery'),
            'input_output_formats'   => $r->list('input_output_formats'),
            'customers'              => $r->list('customers'),

            'status'                 => $r->list('status'),
            'intake_query'           => $r->list('intake_query'),
            'lease_types'            => $r->list('lease_types'),
            'work_types'             => $r->list('work_types'),
            'languages'              => $r->list('languages'),
            'query_status'           => $r->list('query_status'),
            'feedback_categories'    => $r->list('feedback_categories'),
            'invoice_formats'        => $r->list('invoice_formats'),
        ];
    }
}
