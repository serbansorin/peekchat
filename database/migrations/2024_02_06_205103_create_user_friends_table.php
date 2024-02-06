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
        Schema::create('user_friends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('user_friends_user_id_foreign');
            $table->unsignedBigInteger('friend_id')->index('user_friends_friend_id_foreign');
            $table->boolean('accepted')->nullable()->default(false);
            $table->boolean('blocked')->nullable()->default(false);
            $table->boolean('follow')->nullable()->default(true);
            $table->boolean('friend')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_friends');
    }
};
