<?php

use App\Models\Option;
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
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Option::class)->index()->constrained()->cascadeOnDelete();
            $table->string('value');
            $table->float('price')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Option::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Value::class)->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
