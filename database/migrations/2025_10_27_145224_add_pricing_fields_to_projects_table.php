<?php
// database/migrations/xxxx_xx_xx_xxxxxx_fix_pricing_fields_on_projects_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('projects', 'pricing_type')) {
            Schema::table('projects', function (Blueprint $table) {
                // Use enum, or switch to string+check if you prefer
                $table->enum('pricing_type', ['standard', 'fixed'])
                      ->default('standard')
                      ->after('pricing_id');
            });
        }
    }

    public function down(): void
    {
       

        // Drop columns if present
        if (Schema::hasColumn('projects', 'pricing_type')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('pricing_type');
            });
        }
        
    }
};
