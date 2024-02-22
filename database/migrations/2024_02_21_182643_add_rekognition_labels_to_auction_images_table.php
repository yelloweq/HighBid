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
        Schema::table('auction_images', function (Blueprint $table) {
            $table->text('rekognition_labels')->nullable();
            $table->boolean('flagged')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auction_images', function (Blueprint $table) {
            $table->dropColumn('rekognition_labels');
            $table->dropColumn('flagged');
        });
    }
};
