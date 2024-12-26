<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE product_properties ALTER COLUMN title TYPE VARCHAR USING title::VARCHAR");
    }

    public function down(): void
    {
        DB::statement("DO $$ BEGIN
            IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'product_properties_title_enum') THEN
                CREATE TYPE product_properties_title_enum AS ENUM ('property', 'recommendation');
            END IF;
        END $$;");

        DB::statement("ALTER TABLE product_properties ALTER COLUMN title TYPE product_properties_title_enum USING title::product_properties_title_enum");
    }
};


