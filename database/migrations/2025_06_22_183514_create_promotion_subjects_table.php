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
        Schema::create('promotion_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotion_sector_id');
            $table->unsignedBigInteger('subject_id');
            $table->timestamps();
            $table->foreign('promotion_sector_id')->references('id')->on('promotion_sectors')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_subjects');
    }
};
