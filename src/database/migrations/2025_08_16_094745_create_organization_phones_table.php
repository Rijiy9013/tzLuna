<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organization_phones', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('organization_id');
            $t->string('phone_display');
            $t->string('phone_normalized');
            $t->timestamps();

            $t->foreign('organization_id')
                ->references('id')->on('organizations')
                ->cascadeOnDelete();

            $t->unique(['organization_id', 'phone_normalized']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_phones');
    }
};
