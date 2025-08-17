<?php

namespace App\Domain\Organization\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrganizationRepository
{
    public function findWithRelations(string $id): ?array;

    public function paginateByBuilding(string $buildingId, int $perPage = 50): LengthAwarePaginator;

    public function paginateByActivityIds(array $activityIds, int $perPage = 50): LengthAwarePaginator;

    public function paginateByName(string $q, int $perPage = 20): LengthAwarePaginator;
}
