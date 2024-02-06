<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('chat_rooms_user_id_foreign');
            $table->string('uid')->index();
            $table->string('name')->nullable()->default('Chat Room');
            $table->string('description')->nullable()->default('Chat Room Description');
            $table->string('image')->nullable();
            $table->string('status')->nullable()->default('Active')->index();
            $table->unsignedTinyInteger('room_limit')->nullable()->default(2);
            $table->timestamps();
            $table->json('users_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_rooms');
    }
};
