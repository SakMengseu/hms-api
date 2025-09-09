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
        Schema::create('patient_diseases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('disease_id');
            $table->date('diagnosed_date')->nullable();
            $table->unique(['patient_id', 'disease_id', 'diagnosed_date'], 'uq_pd');
            $table->foreign('patient_id', 'fk_pd_patient')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('disease_id', 'fk_pd_disease')
                ->references('id')->on('diseases')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_diseases');
    }
};
