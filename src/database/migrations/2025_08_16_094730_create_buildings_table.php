<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');

        Schema::create('buildings', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('address');
            $t->timestamps();
        });

        DB::statement("ALTER TABLE buildings ADD COLUMN location geography(Point,4326) NOT NULL");
    }

    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
