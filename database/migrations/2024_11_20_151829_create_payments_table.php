<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->string('transaction_id')->nullable();
        $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'paypal', 'stripe'])->default('cash');
        $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
        $table->date('payment_date');
        $table->text('notes')->nullable();
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
