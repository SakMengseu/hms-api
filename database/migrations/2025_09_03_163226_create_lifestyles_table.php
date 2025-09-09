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
        Schema::create('lifestyles', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->foreignId('patient_id')->index()->nullable()->constrained('patients')->onUpdate('set null')->onDelete('set null');

            $table->boolean('smoking')->default(false);
            $table->boolean('alcohol')->default(false);
            $table->boolean('exercise')->default(false);
            $table->boolean('use_traditional_medicine')->default(false);
            $table->text('other')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifestyles');
    }
};
