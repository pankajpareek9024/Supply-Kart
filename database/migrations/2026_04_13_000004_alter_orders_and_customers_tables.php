<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add delivery_boy_id to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_boy_id')) {
                $table->foreignId('delivery_boy_id')->nullable()->constrained('delivery_boys')->nullOnDelete()->after('delivery_boy_phone');
            }
        });

        // Add is_active & email to customers
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('mobile');
            }
            if (!Schema::hasColumn('customers', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('otp_expire_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_boy_id']);
            $table->dropColumn('delivery_boy_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['email', 'is_active']);
        });
    }
};
