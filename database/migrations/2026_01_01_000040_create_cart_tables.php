<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->unique();
            $table->string('mode', 12)->default('retail');    // retail | wholesale
            $table->foreignId('hub_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('shade')->nullable();              // resolved shade value
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_price');            // frozen at resolution
            $table->string('mode', 12)->default('retail');
            $table->timestamps();

            $table->unique(['cart_id', 'product_id', 'shade', 'mode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
