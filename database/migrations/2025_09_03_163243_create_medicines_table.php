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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->string('name', 100);
            $table->string('brand_name', 100)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->unique(['name', 'brand_name'], 'uq_medicine_name_brand');
            $table->index('category_id', 'idx_medicine_category');
            $table->foreign('category_id', 'fk_medicine_category')
                ->references('id')->on('medicine_categories')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
