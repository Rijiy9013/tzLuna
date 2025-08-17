<?php

namespace App\Providers;

use App\Domain\Organization\Repository\OrganizationRepository;
use App\Infrastructure\Persistence\Eloquent\Repository\EloquentOrganizationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OrganizationRepository::class, EloquentOrganizationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
