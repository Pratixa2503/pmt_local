<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('conversation_stats', function (Blueprint $t) {
      $t->uuid('conversation_id')->primary();
      $t->unsignedBigInteger('message_count')->default(0);
      $t->uuid('last_message_id')->nullable();
      $t->string('last_message_preview', 191)->nullable();
      $t->timestamp('last_message_at')->nullable();
      $t->timestamp('updated_at')->nullable();

      $t->foreign('conversation_id')->references('id')->on('conversations')->cascadeOnDelete();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('conversation_stats');
  }
};
