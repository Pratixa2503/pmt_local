<?php

// database/migrations/xxxx_xx_xx_create_banks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('account_holder_name');
            $table->string('account_number')->unique();
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('bank_type')->nullable(); // Saving, Current, etc.
            $table->string('upi_id')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('status')
                  ->default(1)
                  ->comment('0 = FALSE, 1 = TRUE');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};

