<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class MasterDataResolver
{
    /**
     * Simple in-memory cache for this PHP request.
     * [type => [normalizedKey => id]]
     * @var array<string, array<string, int>>
     */
    protected array $memory = [];

    public function __construct(
        protected array $config = []
    ) {
        $this->config = $config ?: config('masterdata', []);
    }

    /**
     * Resolve an ID for a master "type" by a label or code.
     * If not found, create with status=1 (as per config fill definition).
     *
     * @param  string             $type   e.g. 'project_types', 'languages'
     * @param  string|null        $value  label/code from UI/CSV (nullable tolerated)
     * @param  array<string,mixed> $extra Optional extra fill attrs (e.g., ['name'=>'French'])
     * @return int|null
     */
    public function resolveId(string $type, ?string $value, array $extra = []): ?int
    {
        
        if (!isset($this->config[$type])) {
           // dd($this->config[$type]);
            throw new \InvalidArgumentException("Unknown master type: {$type}");
        }
 
        if ($value === null || trim($value) === '') {
            return null;
        }

        $valueNorm = $this->normalize($value);
         
        // runtime memory cache hit
        if (isset($this->memory[$type][$valueNorm])) {
            return $this->memory[$type][$valueNorm];
        }
       
        $modelClass = $this->config[$type]['model'];
        $uniqueBy   = $this->config[$type]['unique_by'] ?? ['name'];
        $fill       = $this->config[$type]['fill']      ?? ['name' => null, 'status' => 1];
      
        /** @var Model $model */
        $model = new $modelClass;
        
        // Try find by any of the unique_by columns (case-insensitive)
        $query = $modelClass::query();
         
        foreach ($uniqueBy as $col) {
            // Try exact match first (case-insensitive)
            $query->orWhereRaw('LOWER(TRIM(' . $model->getConnection()->getQueryGrammar()->wrap($col) . ')) = ?', [$valueNorm]);
        }

        $found = $query->first();
       
        if ($found) {
            $id = (int) $found->getKey();
            return $this->memory[$type][$valueNorm] = $id;
        }

        // Build payload for create()
        $payload = $fill;

        // Fill primary label/code columns from $value when null
        foreach ($payload as $k => $v) {
            if ($v === null && in_array($k, $uniqueBy, true)) {
                $payload[$k] = $value;
            }
        }

        // Merge extras (e.g., name for languages when only code provided)
        foreach ($extra as $k => $v) {
            if (!array_key_exists($k, $payload) || $payload[$k] === null) {
                $payload[$k] = $v;
            }
        }

        $created = $modelClass::create($payload);
        $id = (int) $created->getKey();
        return $this->memory[$type][$valueNorm] = $id;
    }

    /**
     * Fetch a full list for UI selects (status=1 preferred).
     * Falls back to all if status column not present.
     */
    public function list(string $type)
    {
        if (!isset($this->config[$type])) {
            throw new \InvalidArgumentException("Unknown master type: {$type}");
        }
        $modelClass = $this->config[$type]['model'];

        $model  = new $modelClass;
        $schema = $model->getConnection()->getSchemaBuilder();
        $table  = $model->getTable();

        $hasStatus = $schema->hasColumn($table, 'status');

        $q = $modelClass::query();
        if ($hasStatus) $q->where('status', 1);

        // prefer "name" sorting if column exists
        if ($schema->hasColumn($table, 'name')) {
            $q->orderBy('name');
        }

        return $q->get();
    }

    /**
     * Utility: normalize key for lookups.
     */
    protected function normalize(string $s): string
    {
        return Str::lower(trim(preg_replace('/\s+/', ' ', $s)));
    }
}
