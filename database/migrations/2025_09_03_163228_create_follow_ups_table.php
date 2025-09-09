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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->date('follow_up_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->foreign('patient_id', 'fk_fu_patient')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id', 'fk_fu_doctor')
                ->references('id')->on('doctors')->onDelete('restrict');
            $table->foreign('consultation_id')
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
        Schema::dropIfExists('follow_ups');
    }
};
