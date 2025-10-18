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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('full_name', 100)->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('specialty', 100)->nullable();
            $table->string('license_number', 50)->nullable()->unique('uq_doctor_license');
            $table->string('department', 100)->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('commune_id')->nullable();
            $table->integer('village_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id', 'fk_doctor_user')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
