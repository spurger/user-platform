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
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->on('users')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('recipient_id');
            $table->foreign('recipient_id')->on('users')->references('id')->onDelete('cascade');

            $table->boolean('seen_by_recipient')->default(false);

            $table->boolean('refused')->default(false);

            $table->unique(['sender_id', 'recipient_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friend_requests');
    }
};
