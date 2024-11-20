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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('currency')->default('USD');
            $table->decimal('default_tax_rate', 5, 2)->default(0);
            $table->string('invoice_prefix')->default('INV-');
            $table->integer('invoice_starting_number')->default(1);
            $table->text('invoice_footer_text')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
