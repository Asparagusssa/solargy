<?php

use App\Models\Product;
use App\Models\Value;
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
        Schema::create('product_option_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->json('options');
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_prices');
    }
};
