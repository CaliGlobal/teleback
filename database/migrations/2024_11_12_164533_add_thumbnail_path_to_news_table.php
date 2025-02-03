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
        Schema::table('news_table', function (Blueprint $table) {
            // Adding the thumbnailPath column as a nullable string
            $table->string('thumbnailPath')->nullable(); // You can adjust the type if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_table', function (Blueprint $table) {
            // Drop the thumbnailPath column if the migration is rolled back
            $table->dropColumn('thumbnailPath');
        });
    }
};
