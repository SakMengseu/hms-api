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
            $table->string('specialty', 100)->nullable();
            $table->string('license_number', 50)->nullable()->unique('uq_doctor_license');
            $table->string('department', 100)->nullable();
            $table->integer('experience_years')->nullable();

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
