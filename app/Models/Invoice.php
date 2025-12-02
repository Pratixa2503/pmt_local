<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id','customer_id','billing_month',
        'po_number','invoice_no','invoice_date','due_date',
        'status','created_by','assigned_to',
        'currency_id','currency_name','currency_symbol',
        'gross_total','discount_total','tax_total','net_total',
        'company_name','company_address','company_pan','company_gstin',
        'company_lut_no','company_iec','company_reference_no','company_signatory',
        'customer_name','customer_address','customer_type','description','subtotal',
        'discount','total','bank_id','payment_completed','payment_completed_date','finance_notes','sac_number',
        'customer_zipcode','invoice_type'
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function customer() {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
   
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
