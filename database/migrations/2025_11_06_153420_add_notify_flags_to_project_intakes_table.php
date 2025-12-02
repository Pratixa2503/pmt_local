<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_intakes', function (Blueprint $t) {
            $t->date('abstract_notified_on')->nullable()->after('abstract_completion_date');
            $t->date('review_notified_on')->nullable()->after('review_completion_date');
            $t->date('sense_notified_on')->nullable()->after('sense_check_completion_date');
        });
    }

    public function down(): void
    {
        Schema::table('project_intakes', function (Blueprint $t) {
            $t->dropColumn(['abstract_notified_on', 'review_notified_on', 'sense_notified_on']);
        });
    }
};
