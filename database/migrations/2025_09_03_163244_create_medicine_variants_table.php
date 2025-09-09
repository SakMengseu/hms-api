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
        Schema::create('medicine_variants', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('medicine_id');
            $table->unsignedBigInteger('form_id');
            $table->string('dosage', 50)->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sell_price', 10, 2)->nullable();
            $table->unique(['medicine_id', 'form_id', 'dosage', 'unit', 'price', 'sell_price'], 'uq_variant');
            $table->index('medicine_id', 'idx_variant_medicine');
            $table->foreign('medicine_id', 'fk_variant_medicine')
                ->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('form_id', 'fk_variant_form')
                ->references('id')->on('medicine_forms')->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_variants');
    }
};
