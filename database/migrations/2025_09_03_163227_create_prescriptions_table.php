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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number', 100)->unique('uq_prescription_number');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('consultation_id');
            $table->unsignedBigInteger('follow_up_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->date('date');
            $table->text('notes')->nullable();
            
            $table->foreign('patient_id', 'fk_presc_patient')
                ->references('id')->on('patients')->onDelete('restrict');
            $table->foreign('doctor_id', 'fk_presc_doctor')
                ->references('id')->on('doctors')->onDelete('restrict');
            $table->foreign('consultation_id', 'fk_presc_consult')
                ->references('id')->on('consultations')->onDelete('cascade');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
