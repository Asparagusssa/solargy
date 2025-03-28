<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->string('photo');
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_photos');
    }
};
