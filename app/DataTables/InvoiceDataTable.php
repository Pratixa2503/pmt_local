<?php

namespace App\DataTables;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class InvoiceDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('project_name', fn(Invoice $r) => optional($r->project)->project_name ?? '-')
            ->addColumn('customer_name', fn(Invoice $r) => optional($r->customer)->name ?? '-')
            ->addColumn('amount', function (Invoice $r) {
                $sym = $r->currency_symbol ?? '';
                return $sym . ' ' . number_format((float) ($r->total ?? 0), 2);
            })
            ->editColumn('status', function (Invoice $r) {
                $map = [
                    'draft'            => 'secondary',
                    'submitted'        => 'info',
                    'finance_approved' => 'success',
                    'sent'             => 'primary',
                    'rejected'         => 'danger',
                    'payment_completed' => 'success',
                ];
                $class = $map[$r->status] ?? 'secondary';
                return '<span class="badge bg-' . e($class) . '">' . e(ucwords(str_replace('_', ' ', $r->status))) . '</span>';
            })
            ->editColumn('created_at', fn(Invoice $r) => optional($r->created_at)->format('d M Y, H:i'))
            ->addColumn('payment_completed_date', function (Invoice $r) {
                if ($r->payment_completed && $r->payment_completed_date) {
                    try {
                        return Carbon::parse($r->payment_completed_date)->format('m-d-Y');
                    } catch (\Exception $e) {
                        return '-';
                    }
                }
                return '-';
            })
            ->addColumn('actions', function (Invoice $r) {
                $encrypted = Crypt::encryptString($r->id);
                $url = route('invoices.show', $encrypted);
                $html = '<a class="btn btn-sm btn-primary" href="' . e($url) . '" title="View Invoice"><i class="fa-solid fa-eye"></i></a>';
                
                // Payment Completed action or icon
                if ($r->status === 'finance_approved' && !$r->payment_completed) {
                    $html .= ' <i class="fa-solid fa-check-circle text-success payment-complete-icon" style="font-size: 1.2rem; cursor: pointer;" data-id="' . e($encrypted) . '" title="Mark Payment Completed"></i>';
                } elseif ($r->status === 'payment_completed' && $r->payment_completed_date) {
                    try {
                        $dateStr = Carbon::parse($r->payment_completed_date)->format('m-d-Y');
                    } catch (\Exception $e) {
                        $dateStr = 'N/A';
                    }
                    $html .= ' <i class="fa-solid fa-circle-check text-success" style="font-size: 1.2rem; cursor: help;" title="Payment Completed: ' . e($dateStr) . '"></i>';
                }
                
                return $html;
            })
            ->rawColumns(['status', 'actions']);
    }

    public function query(Invoice $model): Builder
    {
        $req = $this->request();

        return $model->newQuery()
            ->with(['project:id,project_name', 'customer:id,name'])
            ->select('invoices.*')
            ->when($req->get('month'),        fn($q, $v) => $q->where('billing_month', $v))
            ->when($req->get('project_id'),   fn($q, $v) => $q->where('project_id', $v))
            ->when($req->get('customer_id'),  fn($q, $v) => $q->where('customer_id', $v))
            ->whereNull('deleted_at')
            ->orderByDesc('id');
    }

   public function html()
{
   return $this->builder()
    ->setTableId('invoice-table')
    ->columns($this->getColumns())
    ->ajax([
        'url'  => route('invoices.list'),
        'type' => 'GET',
        'data' => 'function(d){
            // normalize to MM-YYYY
            var v = ($("#filter_month").val() || "").trim();
            var m;
            if ((m = /^(\d{4})-(\d{2})$/.exec(v))) {        // YYYY-MM -> MM-YYYY
              v = m[2] + "-" + m[1];
            } else if ((m = /^(\d{2})[-\/](\d{4})$/.exec(v))) { // MM-YYYY or MM/YYYY -> MM-YYYY
              v = m[1] + "-" + m[2];
            }
            d.month       = v;
            d.project_id  = $("#filter_project").val();
            d.customer_id = $("#filter_customer").val();
        }',
    ])
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
            Column::make('invoice_no')->title('Invoice #'),
            Column::computed('project_name')->title('Project')->orderable(false),
            Column::computed('customer_name')->title('Customer')->orderable(false),
            Column::make('billing_month')->title('Billing Month'),
            Column::make('status')->title('Status')->addClass('text-nowrap')->orderable(false),
            Column::computed('amount')->title('Total')->addClass('text-end')->orderable(false)->searchable(false),
            Column::computed('payment_completed_date')->title('Payment Completed Date')->orderable(false)->searchable(false),
            Column::make('created_at')->title('Created'),
            Column::computed('actions')->title('Actions')->orderable(false)->searchable(false)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'invoices_' . now()->format('Ymd_His');
    }
}
