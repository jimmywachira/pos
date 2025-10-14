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
        Schema::create('sales', function (Blueprint $table) {
             $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('user_id')->constrained(); // cashier
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->decimal('total', 14, 2);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('paid', 14, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->json('meta')->nullable(); // additional data like split payments
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
