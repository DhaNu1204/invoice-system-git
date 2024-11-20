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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->string('invoice_number')->unique();
        $table->date('issue_date');
        $table->date('due_date');
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('tax_rate', 5, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->text('notes')->nullable();
        $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};