<?php

use App\Models\Product;
use App\Models\PurchasePlace;
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
        Schema::create('market_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PurchasePlace::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_product');
    }
};
