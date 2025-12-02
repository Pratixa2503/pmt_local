<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
class Company extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
    public const TYPE_INDIAN     = 1;
    public const TYPE_NON_INDIAN = 2;
    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'name',
        'address',
        'location',
        'contact_no',
        'website',
        'company_type',
        'zip_code',
        'invoice_type' 
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'customer_id'); // FK on projects table
    }
    
    public function poNumbers()
    {
        return $this->hasMany(PoNumber::class, 'customer_id');
    }
    /**
     * Attribute casting.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'company_type' => 'integer',
        'zip_code'  => 'string',
        'invoice_type' => 'integer'
    ];

    /**
     * Relationships
     * -----------
     */

    // All users (team members) belonging to this company
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Convenience relation for active users (status stored as 'active' / 'inactive')
    public function activeUsers()
    {
        return $this->users()->where('status', 'active');
    }

    /**
     * Accessors & Mutators (Laravel 9+ style)
     * ---------------------------------------
     */

    // Trim company name on set
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_string($value) ? trim($value) : $value
        );
    }

    // Normalize website: store without scheme; read with https:// if missing
    protected function website(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
                $value
                    ? (preg_match('#^https?://#i', $value) ? $value : "https://{$value}")
                    : null,
            set: fn ($value) =>
                $value
                    ? preg_replace('#^https?://#i', '', trim($value))
                    : null
        );
    }

    /**
     * Query Scopes
     * ------------
     */

    // Quick text search over common columns
    public function scopeSearch($query, ?string $term)
    {
        $term = trim((string) $term);

        return $query->when($term !== '', function ($q) use ($term) {
            $q->where(function ($q2) use ($term) {
                $q2->where('name', 'like', "%{$term}%")
                   ->orWhere('location', 'like', "%{$term}%")
                   ->orWhere('address', 'like', "%{$term}%");
            });
        });
    }

    // Eager-load counts quickly (helpful for listings)
    public function scopeWithTeamCounts($query)
    {
        return $query->withCount([
            'users',
            'activeUsers',
        ]);
    }

    public function getCompanyTypeLabelAttribute(): string
    {
        return match ($this->company_type) {
            self::TYPE_NON_INDIAN => 'Non-Indian',
            default               => 'Indian',
        };
    }
}
