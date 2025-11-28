<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('message_reads', function (Blueprint $t) {
      $t->id();
      $t->uuid('message_id');
      $t->string('participant_type');            // App\Models\User | App\Models\ClientContact
      $t->unsignedBigInteger('participant_id');
      $t->timestamp('read_at')->useCurrent();
      $t->timestamps();

      $t->foreign('message_id')->references('id')->on('messages')->cascadeOnDelete();
      $t->unique(['message_id','participant_type','participant_id'], 'message_read_unique');
      $t->index(['participant_type','participant_id','read_at']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('message_reads');
  }
};
