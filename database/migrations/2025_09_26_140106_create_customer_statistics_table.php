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
        Schema::create('customer_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->decimal('total_amount_paid', 12, 2)->default(0);
            $table->decimal('total_amount_refunded', 12, 2)->default(0);

            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('total_refunded_orders')->default(0);
            $table->unsignedInteger('total_items')->default(0);

            $table->timestamp('first_order_date')->nullable();
            $table->timestamp('last_order_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_statistics');
    }
};
