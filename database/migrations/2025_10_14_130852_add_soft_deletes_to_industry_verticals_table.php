<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_soft_deletes_to_industry_verticals_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('industry_verticals', function (Blueprint $table) {
            if (!Schema::hasColumn('industry_verticals', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
                $table->index('deleted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('industry_verticals', function (Blueprint $table) {
            if (Schema::hasColumn('industry_verticals', 'deleted_at')) {
                $table->dropIndex(['deleted_at']);
                $table->dropSoftDeletes();
            }
        });
    }
};
