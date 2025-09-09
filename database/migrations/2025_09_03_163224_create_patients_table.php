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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();

            $table->string('code', 100)->nullable()->unique('uq_patient_code');
            $table->string('full_name', 100)->nullable();
            $table->string('latin_name', 100)->nullable();
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
            $table->date('dob')->nullable();
            $table->string('phone', 20)->nullable()->index('idx_patient_phone');
            $table->string('nationality', 100)->nullable();
            $table->year('year_of_diagnosis')->nullable();
            $table->string('occupation', 150)->nullable();
            $table->string('status', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();

            $table->index(['province_id', 'district_id', 'commune_id', 'village_id'], 'idx_patient_location');
            $table->foreign('province_id', 'fk_patient_province')
                ->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('district_id', 'fk_patient_district')
                ->references('id')->on('districts')->onDelete('set null');
            $table->foreign('commune_id', 'fk_patient_commune')
                ->references('id')->on('communes')->onDelete('set null');
            $table->foreign('village_id', 'fk_patient_village')
                ->references('id')->on('villages')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
