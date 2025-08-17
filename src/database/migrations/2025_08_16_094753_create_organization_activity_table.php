<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organization_activity', function (Blueprint $t) {
            $t->uuid('organization_id');
            $t->uuid('activity_id');

            $t->primary(['organization_id', 'activity_id']);

            $t->foreign('organization_id')
                ->references('id')->on('organizations')
                ->cascadeOnDelete();

            $t->foreign('activity_id')
                ->references('id')->on('activities')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_activity');
    }
};
