<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingMaster extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'pricing_type',
        'industry_vertical_id',
        'department_id',
        'service_offering_id',
        'unit_of_measurement_id',
        'description_id',
        'currency_id',
        'rate',
        'project_management_cost',
        'vendor_cost',
        'infrastructure_cost',
        'overhead_percentage',
        'margin_percentage',
        'volume',
        'volume_based_discount',
        'conversion_rate',
        'name',
        'status',
        'document_path',
        'approval_note',
        'custom_pricing_type',
        'customer_id',
        'modification_notes',
        'modification_parameter'
    ];

     protected $casts = [
        'status' => 'boolean',
        'rate'   => 'decimal:2',
        'project_management_cost' => 'decimal:2',
        'vendor_cost'             => 'decimal:2',
        'infrastructure_cost'     => 'decimal:2',
        'overhead_percentage'     => 'decimal:2',
        'margin_percentage'       => 'decimal:2',
        'volume'                  => 'decimal:2',
        'volume_based_discount'   => 'decimal:2',
        'conversion_rate'         => 'decimal:4',
        'status'       => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public function skillLines()
    {
        return $this->hasMany(PricingMasterSkillLine::class);
    }
    
    public function industryVertical()
    {
        return $this->belongsTo(IndustryVertical::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function serviceOffering()
    {
        return $this->belongsTo(ServiceOffering::class);
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function description()
    {
        return $this->belongsTo(Description::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
