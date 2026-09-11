<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('session_id', 64);
            $table->foreignId('hub_id')->constrained();
            $table->string('mode', 12);
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('delivery_fee');
            $table->unsignedBigInteger('total');
            $table->string('delivery_zone')->nullable();
            $table->string('delivery_method')->default('delivery');
            $table->string('destination_state')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('status', 24)->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->string('name_snapshot');
            $table->string('shade')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_price');
            $table->unsignedBigInteger('line_total');
            $table->timestamps();
        });

        Schema::create('whatsapp_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('mode', 12);
            $table->foreignId('hub_id')->constrained();
            $table->text('summary');
            $table->string('deep_link');
            $table->string('receipt_state', 16)->default('NONE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
