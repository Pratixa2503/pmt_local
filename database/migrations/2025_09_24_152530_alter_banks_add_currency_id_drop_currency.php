<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            if (!Schema::hasColumn('banks', 'entity')) {
                $table->string('entity')->nullable()->after('id');
            }
            if (!Schema::hasColumn('banks', 'currency_id')) {
                $table->unsignedBigInteger('currency_id')->nullable()->after('entity');
            }
            if (!Schema::hasColumn('banks', 'account_name')) {
                $table->string('account_name')->nullable()->after('currency_id');
            }
            if (!Schema::hasColumn('banks', 'account_number')) {
                $table->string('account_number')->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('banks', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('banks', 'branch_location')) {
                $table->string('branch_location')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('banks', 'ifsc_code')) {
                $table->string('ifsc_code')->nullable()->after('branch_location');
            }
            if (!Schema::hasColumn('banks', 'swift_code')) {
                $table->string('swift_code')->nullable()->after('ifsc_code');
            }
            if (!Schema::hasColumn('banks', 'micr')) {
                $table->string('micr')->nullable()->after('swift_code');
            }
            if (!Schema::hasColumn('banks', 'bsr_code')) {
                $table->string('bsr_code')->nullable()->after('micr');
            }
            if (!Schema::hasColumn('banks', 'branch_address')) {
                $table->text('branch_address')->nullable()->after('bsr_code');
            }
        });

        // Add FK for currency_id
        Schema::table('banks', function (Blueprint $table) {
            if (Schema::hasColumn('banks', 'currency_id')) {
                try {
                    $table->foreign('currency_id')
                          ->references('id')
                          ->on('currencies')
                          ->nullOnDelete();
                } catch (\Throwable $e) {
                    // ignore if already exists
                }
            }
        });

        // Backfill account_name <- account_holder_name
        if (Schema::hasColumn('banks', 'account_holder_name') && Schema::hasColumn('banks', 'account_name')) {
            DB::table('banks')
              ->whereNull('account_name')
              ->update(['account_name' => DB::raw('account_holder_name')]);
        }

        // Backfill branch_location <- branch_name
        if (Schema::hasColumn('banks', 'branch_name') && Schema::hasColumn('banks', 'branch_location')) {
            DB::table('banks')
              ->whereNull('branch_location')
              ->update(['branch_location' => DB::raw('branch_name')]);
        }

        // Backfill currency_id from old currency text
        if (Schema::hasColumn('banks', 'currency') && Schema::hasColumn('banks', 'currency_id')) {
            DB::statement("
                UPDATE banks b
                LEFT JOIN currencies c
                    ON UPPER(TRIM(c.name)) = UPPER(TRIM(b.currency))
                SET b.currency_id = c.id
                WHERE b.currency_id IS NULL
            ");
        }

        // Drop old columns
        Schema::table('banks', function (Blueprint $table) {
            if (Schema::hasColumn('banks', 'account_holder_name')) {
                $table->dropColumn('account_holder_name');
            }
            if (Schema::hasColumn('banks', 'branch_name')) {
                $table->dropColumn('branch_name');
            }
            if (Schema::hasColumn('banks', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }

    public function down(): void
    {
        // Recreate old columns
        Schema::table('banks', function (Blueprint $table) {
            if (!Schema::hasColumn('banks', 'currency')) {
                $table->string('currency')->nullable();
            }
            if (!Schema::hasColumn('banks', 'account_holder_name')) {
                $table->string('account_holder_name')->nullable();
            }
            if (!Schema::hasColumn('banks', 'branch_name')) {
                $table->string('branch_name')->nullable();
            }
        });

        // Backfill old columns from new ones
        if (Schema::hasColumn('banks', 'currency') && Schema::hasColumn('banks', 'currency_id')) {
            DB::statement("
                UPDATE banks b
                LEFT JOIN currencies c ON c.id = b.currency_id
                SET b.currency = c.name
                WHERE b.currency IS NULL
            ");
        }
        if (Schema::hasColumn('banks', 'account_holder_name') && Schema::hasColumn('banks', 'account_name')) {
            DB::table('banks')
              ->whereNull('account_holder_name')
              ->update(['account_holder_name' => DB::raw('account_name')]);
        }
        if (Schema::hasColumn('banks', 'branch_name') && Schema::hasColumn('banks', 'branch_location')) {
            DB::table('banks')
              ->whereNull('branch_name')
              ->update(['branch_name' => DB::raw('branch_location')]);
        }

        // Drop new columns
        Schema::table('banks', function (Blueprint $table) {
            if (Schema::hasColumn('banks', 'currency_id')) {
                try {
                    $table->dropForeign(['currency_id']);
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            foreach ([
                'entity',
                'currency_id',
                'account_name',
                'account_number',
                'bank_name',
                'branch_location',
                'ifsc_code',
                'swift_code',
                'micr',
                'bsr_code',
                'branch_address',
            ] as $col) {
                if (Schema::hasColumn('banks', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
