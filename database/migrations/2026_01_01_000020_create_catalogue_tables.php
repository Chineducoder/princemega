<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('nav_label')->nullable();   // SHOP / SKINCARE / …
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();    // Editorial sub-line
            $table->string('micro_label');             // CLINICAL SKINCARE …
            $table->text('description')->nullable();
            $table->unsignedInteger('retail_price');   // whole naira
            $table->json('wholesale_tiers');           // {min: price}
            $table->unsignedInteger('moq')->default(1);// wholesale minimum
            $table->string('pack_note')->nullable();   // PACK OF 6
            $table->json('shades')->nullable();        // [{name,value,type}]
            $table->json('images');                    // [{src,alt,w,h}]
            $table->float('weight_kg', 6, 2)->default(0.5);
            $table->float('volume_m3', 8, 4)->default(0.004);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};
