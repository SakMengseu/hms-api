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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->dateTime('appointment_date');
            $table->string('status', 20);
            $table->text('notes')->nullable();
            $table->index('appointment_date', 'idx_appointments_date');
            $table->foreign('patient_id', 'fk_appt_patient')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id', 'fk_appt_doctor')
                ->references('id')->on('doctors')->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
