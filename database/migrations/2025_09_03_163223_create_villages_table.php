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
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->foreignId('province_id')->index()->nullable()->constrained('provinces')->onUpdate('set null')->onDelete('set null');
            $table->foreignId('district_id')->index()->nullable()->constrained('districts')->onUpdate('set null')->onDelete('set null');
            $table->foreignId('commune_id')->index()->nullable()->constrained('communes')->onUpdate('set null')->onDelete('set null');

            $table->string('code')->unique()->nullable();
            $table->string('number')->unique()->nullable();
            $table->string('name', 2000)->nullable();
            $table->string('latin_name', 2000)->nullable();
            $table->string('full_name', 2000)->nullable();
            $table->string('address', 2000)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
