<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Use boolean (maps to TINYINT(1) on MySQL)
            $table->boolean('is_billing_contact')->default(false)->after('email');
            $table->boolean('is_project_contact')->default(false)->after('is_billing_contact');

            // Optional: add indexes if you'll frequently filter by these
            $table->index('is_billing_contact');
            $table->index('is_project_contact');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_billing_contact']);
            $table->dropIndex(['is_project_contact']);
            $table->dropColumn(['is_billing_contact', 'is_project_contact']);
        });
    }
};
