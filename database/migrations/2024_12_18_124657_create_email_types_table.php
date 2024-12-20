<?php

use App\Models\Email;
use App\Models\EmailType;
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
        Schema::create('email_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('email_email_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Email::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(EmailType::class)->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_types');
        Schema::dropIfExists('email_email_type');
    }
};
