<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update delivery_boys table
        Schema::table('delivery_boys', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_boys', 'email')) {
                $table->string('email')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('delivery_boys', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            $table->rememberToken()->after('password');
        });

        // Update orders table with tracking timestamps
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('delivery_boy_id');
            }
            if (!Schema::hasColumn('orders', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('assigned_at');
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('picked_up_at');
            }
        });

        // Create Order Status History for tracking timeline
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->string('comment')->nullable();
            $table->string('location')->nullable(); // Lat/Lng if available
            $table->timestamps();
        });

        // Create Reviews table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_boy_id')->nullable()->constrained('delivery_boys')->nullOnDelete();
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_status_history');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['assigned_at', 'picked_up_at', 'delivered_at']);
        });

        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'remember_token']);
        });
    }
};
