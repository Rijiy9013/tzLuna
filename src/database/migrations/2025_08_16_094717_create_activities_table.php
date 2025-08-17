<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('name');
            $t->string('slug')->unique();
            $t->uuid('parent_id')->nullable();
            $t->smallInteger('level');
            $t->timestamps();

            $t->unique(['parent_id', 'name']);
        });

        DB::statement("
            ALTER TABLE activities
            ADD CONSTRAINT chk_activities_level_range
            CHECK (level BETWEEN 1 AND 3)
        ");

        Schema::table('activities', function (Blueprint $t) {
            $t->foreign('parent_id', 'activities_parent_id_fk')
                ->references('id')->on('activities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
