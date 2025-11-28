<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pricing_masters', function (Blueprint $table) {
            //$table->string('name')->after('id');
            //$table->boolean('status')->default(1)->after('name'); // 1 = Active, 0 = Inactive
        });
    }

    public function down()
    {
        Schema::table('pricing_masters', function (Blueprint $table) {
            //$table->dropColumn(['status']);
        });
    }
};
