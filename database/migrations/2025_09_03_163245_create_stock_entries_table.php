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
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->integer('quantity');
            $table->enum('type', ['IN', 'OUT']);
            $table->string('source', 50)->nullable();
            $table->text('note')->nullable();
            $table->index('variant_id', 'idx_stock_variant');
            $table->index('type', 'idx_stock_type');
            $table->foreign('variant_id', 'fk_stock_variant')
                ->references('id')->on('medicine_variants')->onDelete('cascade');
            $table->foreign('consultation_id', 'fk_stock_consult')
                ->references('id')->on('consultations')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
