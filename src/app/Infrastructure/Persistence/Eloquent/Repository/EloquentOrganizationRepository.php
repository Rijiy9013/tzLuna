<?php

namespace App\Infrastructure\Persistence\Eloquent\Repository;

use App\Domain\Organization\Repository\OrganizationRepository;
use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentOrganizationRepository implements OrganizationRepository
{
    public function findWithRelations(string $id): ?array
    {
        $m = Organization::with(['building', 'phones', 'activities'])->find($id);
        return $m?->toArray();
    }

    public function paginateByBuilding(string $buildingId, int $perPage = 50): LengthAwarePaginator
    {
        return Organization::with(['phones', 'activities'])
            ->where('building_id', $buildingId)
            ->paginate($perPage);
    }

    public function paginateByActivityIds(array $activityIds, int $perPage = 50): LengthAwarePaginator
    {
        return Organization::with(['building', 'phones'])
            ->whereHas('activities', fn($q) => $q->whereIn('activities.id', $activityIds))
            ->paginate($perPage);
    }

    public function paginateByName(string $q, int $perPage = 20): LengthAwarePaginator
    {
        return Organization::with('building')
            ->where('name', 'ILIKE', "%{$q}%")
            ->paginate($perPage);
    }
}
