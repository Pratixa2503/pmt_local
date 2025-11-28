<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\UserSeeder;
use Spatie\Activitylog\Models\Activity;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        activity()->disableLogging();
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->after('first_name');
            $table->string('contact_no')->after('email');
            $table->string('company_name')->nullable()->after('contact_no');
            $table->tinyInteger('status')
                  ->default(1)
                  ->after('remember_token')
                  ->comment('0 = FALSE, 1 = TRUE');
        });

        $userSeeder = new UserSeeder();
        $userSeeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_name',
                'contact_no',
                'company_name',
                'status',
            ]);
        });
    }
};
