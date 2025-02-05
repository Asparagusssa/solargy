<?php

use App\Models\ProductProperty;
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
        Schema::create('property_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductProperty::class)->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('file');
            $table->string('filename');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_files');
    }
};
