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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('delivery_charge', 8, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'packed', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
            $table->string('payment_method')->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->text('shipping_address');
            $table->string('delivery_boy_name')->nullable();
            $table->string('delivery_boy_phone')->nullable();
            $table->string('delivery_lat')->nullable();
            $table->string('delivery_lng')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
