<?php

namespace App\DataTables;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Crypt;
class InvoiceBrowseDataTable extends DataTable
{
     public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('project_name', fn($r) => optional($r->project)->project_name ?? '-')
            ->addColumn('customer_name', fn($r) => optional($r->customer)->name ?? '-')
            ->addColumn('amount', function ($r) {
                $sym = $r->currency_symbol ?? '';
                return $sym . ' ' . number_format((float) ($r->total ?? 0), 2);
            })
            ->editColumn('status', function ($r) {
                $map = [
                    'draft'            => 'secondary',
                    'submitted'        => 'info',
                    'finance_approved' => 'success',
                    'sent'             => 'primary',
                    'rejected'         => 'danger',
                ];
                $class = $map[$r->status] ?? 'secondary';
                return '<span class="badge bg-' . e($class) . '">' . e(ucwords(str_replace('_', ' ', $r->status))) . '</span>';
            })
            ->editColumn('created_at', fn($r) => optional($r->created_at)->format('d M Y, H:i'))
            ->addColumn('actions', function ($r) {
                $encrypted = Crypt::encryptString($r->id);
                $url = route('invoices.show', $encrypted);
                return '<a class="btn btn-sm btn-primary" href="' . e($url) . '">View</a>';
            })
            ->rawColumns(['status', 'actions']);
    }

    public function query(Invoice $model): Builder
    {
        $req        = $this->request();
        $month      = $req->input('month');        // YYYY-MM
        $projectId  = $req->input('project_id');   // optional
        $customerId = $req->input('customer_id');  // optional
       // dd($month,$projectId,$customerId);
        $user       = auth()->user();
        $canViewAll = $user && ($user->can('view all invoices') || (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('super admin'))));

        return $model->newQuery()
            ->with(['project:id,project_name','customer:id,name'])
            ->select('invoices.*')
            ->when(!$canViewAll, fn($q) => $q->where('created_by', $user?->id ?? 0))
            ->when($month,      fn($q,$v) => $q->where('billing_month', $v))
            ->when($projectId,  fn($q,$v) => $q->where('project_id', $v))
            ->when($customerId, fn($q,$v) => $q->where('customer_id', $v))
            ->whereNull('deleted_at')
            ->orderByDesc('id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('invoice-table')
            ->columns($this->getColumns())
            // keep ajax simple; we’ll change URL with query string from Blade
            ->ajax(route('invoices.general'))
            ->orderBy(0, 'desc')
            ->responsive(true)
            ->parameters([
                'pageLength' => (int) env('DATATABLEPAGELENGTH', 10),
                'language'   => [
                    'emptyTable'        => 'No invoices found',
                    'search'            => '',
                    'searchPlaceholder' => 'Search invoices',
                ],
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('invoice_no')->title('Invoice #'),
            Column::computed('project_name')->title('Project')->orderable(false),
            Column::computed('customer_name')->title('Customer')->orderable(false),
            Column::make('billing_month')->title('Billing Month'),
            Column::make('status')->title('Status')->orderable(false)->addClass('text-nowrap'),
            Column::computed('amount')->title('Total')->orderable(false)->searchable(false)->addClass('text-end'),
            Column::make('created_at')->title('Created'),
            Column::computed('actions')->title('Actions')->orderable(false)->searchable(false)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'invoices_' . now()->format('Ymd_His');
    }
}
