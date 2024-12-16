<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_socials', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->text('url');
            $table->string('image');
            $table->string('image_footer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_socials');
    }
};
