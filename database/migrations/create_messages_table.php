<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // user_id
            $table->foreignId('friend_id')->constrained('users')->nullable(); // friend_id
            $table->string('user_name'); // user_name
            $table->string('chat_id'); // chat_id
            $table->text('text')->nullable();
            $table->string('file')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
