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
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
            ->index()->constrained('products')->onDelete('cascade');
            $table->string('name');
            $table->string('type');
        });
         Schema::create('product_type_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('varition_type_id')
            ->index()->constrained('variation_types')->onDelete('cascade');
            $table->string('name');
        });
         Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->index()->constrained('products')->onDelete('cascade');
            $table->json('variation_type_option_ids');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 20, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
