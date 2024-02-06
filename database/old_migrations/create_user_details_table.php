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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->binary('picture')->nullable();
            // first name and last name are from users table name column, they will be made from the space between them
            $table->string('first_name')->nullable()->index();

            $table->string('email');
            $table->string('phone');
            $table->json('details')->nullable();
            $table->json('following')->nullable();
            $table->json('friends')->nullable();
            $table->json('blocked')->nullable();

            $table->timestamps();

            $table->unique(['email', 'phone']);

        });
        // indexes for email and phone

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
