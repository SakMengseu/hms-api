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
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('patient_id');
            $table->string('test_name', 100)->nullable();
            $table->date('test_date');
            $table->string('test_number', 100)->nullable();
            $table->text('results')->nullable();
            $table->text('notes')->nullable();
            $table->foreign('patient_id', 'fk_labtest_patient')
                ->references('id')->on('patients')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};
