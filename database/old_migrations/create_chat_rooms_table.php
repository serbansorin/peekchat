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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->string('uid')->index();
            $table->string('name')->nullable()->default('Chat Room');
            $table->string('description')->nullable()->default('Chat Room Description');
            $table->string('image')->nullable();
            $table->string('status')->nullable()->default('Active')->index();
            $table->tinyInteger('room_limit')->nullable()->unsigned()->default(2);
            $table->json('users_list')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
