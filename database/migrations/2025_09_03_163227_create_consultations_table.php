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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('bp', 20)->nullable();
            $table->string('bsl_fasting', 50)->nullable();
            $table->string('bsl_random', 50)->nullable();
            $table->string('pr', 50)->nullable();
            $table->decimal('waist_circumference', 5, 2)->nullable();
            $table->text('symptoms')->nullable();
            $table->text('diagnosis')->nullable();
            $table->timestamp('consultation_date')->useCurrent();
            
            $table->foreign('patient_id', 'fk_consult_patient')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id', 'fk_consult_doctor')
                ->references('id')->on('doctors')->onDelete('restrict');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
