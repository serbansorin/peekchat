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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('messages_user_id_foreign');
            $table->unsignedBigInteger('friend_id')->nullable()->index('messages_friend_id_foreign');
            $table->string('chat_id')->nullable()->index('chat_id');
            $table->text('text')->nullable();
            $table->string('file')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->string('user_name', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
