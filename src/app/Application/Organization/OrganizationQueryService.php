<?php

namespace App\Application\Organization;

use App\Domain\Organization\Repository\OrganizationRepository;
use App\Infrastructure\Persistence\Eloquent\Query\ActivityTreeQueries;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrganizationQueryService
{
    public function __construct(
        private OrganizationRepository $repository,
        private ActivityTreeQueries    $activityTree
    )
    {
    }

    public function show(string $organizationId): array
    {
        $data = $this->repository->findWithRelations($organizationId);
        if (!$data) {
            throw new NotFoundHttpException();
        }
        return $data;
    }

    public function builderForSearchByName(string $query): Builder
    {
        return Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->where('name', 'ILIKE', "%{$query}%");
    }

    public function builderForBuilding(string $buildingId): Builder
    {
        return Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->where('building_id', $buildingId);
    }

    public function builderForActivityId(string $activityId): Builder
    {
        $ids = $this->activityTree->descendantsIncludingSelf($activityId);

        return Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->whereHas('activities', fn($q) => $q->whereIn('activities.id', $ids));
    }

    public function builderForActivitySlug(string $slug): Builder
    {
        $activity = Activity::where('slug', $slug)->firstOrFail();
        $ids = $this->activityTree->descendantsIncludingSelf($activity->id);

        return Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->whereHas('activities', fn($q) => $q->whereIn('activities.id', $ids));
    }
}
