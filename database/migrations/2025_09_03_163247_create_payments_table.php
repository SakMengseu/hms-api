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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('prescription_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('method', 30)->nullable();
            $table->string('reference', 100)->nullable();
            $table->enum('status', ['PAID', 'UNPAID'])->default('UNPAID');
            $table->timestamp('paid_at')->nullable();
            $table->foreign('patient_id', 'fk_payment_patient')
                ->references('id')->on('patients')->onDelete('restrict');
            $table->foreign('prescription_id', 'fk_payment_prescription')
                ->references('id')->on('prescriptions')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
