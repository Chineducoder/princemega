<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hub_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hub_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('stock');              // 0 = unavailable at this hub
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->timestamps();

            $table->unique(['hub_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hub_inventory');
    }
};
