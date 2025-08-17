<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CrudOrganizationController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware('api_token')->prefix('v1')->group(function () {
    Route::prefix('activities')->group(function () {
        Route::get('slug/{slug}/organizations', [OrganizationController::class, 'byActivitySlug'])
            ->name('activities.slug.organizations');

        Route::get('', [ActivityController::class, 'index'])->name('activities.index');
        Route::post('', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('{activity}', [ActivityController::class, 'show'])->whereUuid('activity')->name('activities.show');
        Route::put('{activity}', [ActivityController::class, 'update'])->whereUuid('activity')->name('activities.update');
        Route::patch('{activity}', [ActivityController::class, 'update'])->whereUuid('activity')->name('activities.patch');
        Route::delete('{activity}', [ActivityController::class, 'destroy'])->whereUuid('activity')->name('activities.destroy');

        Route::get('{activity}/organizations', [OrganizationController::class, 'byActivity'])
            ->whereUuid('activity')->name('activities.organizations');
    });

    Route::prefix('buildings')->group(function () {
        Route::get('', [BuildingController::class, 'index'])->name('buildings.index');
        Route::post('', [BuildingController::class, 'store'])->name('buildings.store');
        Route::get('{building}', [BuildingController::class, 'show'])->whereUuid('building')->name('buildings.show');
        Route::patch('{building}', [BuildingController::class, 'update'])->whereUuid('building')->name('buildings.patch');
        Route::delete('{building}', [BuildingController::class, 'destroy'])->whereUuid('building')->name('buildings.destroy');

        Route::get('{building}/organizations', [OrganizationController::class, 'byBuilding'])
            ->whereUuid('building')->name('buildings.organizations');
    });

    Route::prefix('organizations')->group(function () {
        Route::get('search', [OrganizationController::class, 'searchByName'])->name('organizations.search');
        Route::get('near', [OrganizationController::class, 'near'])->name('organizations.near');
        Route::get('in-rect', [OrganizationController::class, 'inRect'])->name('organizations.inRect');

        Route::post('', [CrudOrganizationController::class, 'store'])->name('organizations.store');
        Route::patch('{organization}', [CrudOrganizationController::class, 'update'])->whereUuid('organization')->name('organizations.patch');
        Route::delete('{organization}', [CrudOrganizationController::class, 'destroy'])->whereUuid('organization')->name('organizations.destroy');

        Route::get('{organization}', [OrganizationController::class, 'show'])
            ->whereUuid('organization')->name('organizations.show');
    });
});
