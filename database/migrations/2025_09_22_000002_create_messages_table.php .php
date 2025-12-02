<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('messages', function (Blueprint $t) {
      $t->uuid('id')->primary();
      $t->uuid('conversation_id');
      $t->string('sender_type');                 // App\Models\User | App\Models\ClientContact
      $t->unsignedBigInteger('sender_id');
      $t->enum('kind', ['text','system','note'])->default('text')->index();
      $t->longText('body')->nullable();
      $t->json('meta')->nullable();              // mentions, reply_to, etc.
      $t->timestamp('sent_at')->useCurrent();
      $t->timestamps();
      $t->softDeletes();

      $t->foreign('conversation_id')->references('id')->on('conversations')->cascadeOnDelete();
      $t->index(['conversation_id','sent_at','id'], 'conv_time'); // keyset pagination
      $t->index(['sender_type','sender_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('messages');
  }
};
