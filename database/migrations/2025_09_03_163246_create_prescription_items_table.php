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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            
            $table->unsignedBigInteger('prescription_id');
            $table->unsignedBigInteger('variant_id');
            $table->integer('quantity');
            $table->text('instructions')->nullable();
            $table->index('prescription_id', 'idx_pi_prescription');
            $table->foreign('prescription_id', 'fk_pi_prescription')
                ->references('id')->on('prescriptions')->onDelete('cascade');
            $table->foreign('variant_id', 'fk_pi_variant')
                ->references('id')->on('medicine_variants')->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
