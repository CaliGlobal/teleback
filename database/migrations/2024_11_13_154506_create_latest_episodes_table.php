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
        Schema::create('latest_episode', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('show_id');
            $table->text('description')->nullable();
            $table->string('thumbnailPath')->nullable();
            $table->string('url');
            $table->timestamps();

            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latest_episode');
    }
};
