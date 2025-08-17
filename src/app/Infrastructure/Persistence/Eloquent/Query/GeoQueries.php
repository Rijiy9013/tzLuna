<?php

namespace App\Infrastructure\Persistence\Eloquent\Query;

use App\Models\Organization;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class GeoQueries
{
    public function paginateNear(float $lat, float $lng, int $radiusM, int $perPage = 50): LengthAwarePaginator
    {
        $idPage = DB::table('organizations as o')
            ->join('buildings as b', 'b.id', '=', 'o.building_id')
            ->whereRaw(
                'ST_DWithin(b.location, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)',
                [$lng, $lat, $radiusM]
            )
            ->orderBy('o.id')
            ->select('o.id')
            ->paginate($perPage);

        $ids = collect($idPage->items())->pluck('id')->all();

        $models = Organization::with(['building', 'phones', 'activities'])
            ->whereIn('id', $ids)->get()->keyBy('id');

        $items = collect($ids)->map(fn($id) => $models->get($id))->filter();

        return new LengthAwarePaginator(
            $items,
            $idPage->total(),
            $idPage->perPage(),
            $idPage->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function paginateInRect(float $minLat, float $minLng, float $maxLat, float $maxLng, int $perPage = 50): LengthAwarePaginator
    {
        $idPage = DB::table('organizations as o')
            ->join('buildings as b', 'b.id', '=', 'o.building_id')
            ->whereRaw(
                'ST_Within(b.location::geometry, ST_MakeEnvelope(?, ?, ?, ?, 4326))',
                [$minLng, $minLat, $maxLng, $maxLat]
            )
            ->orderBy('o.id')
            ->select('o.id')
            ->paginate($perPage);

        $ids = collect($idPage->items())->pluck('id')->all();

        $models = Organization::with(['building', 'phones', 'activities'])
            ->whereIn('id', $ids)->get()->keyBy('id');

        $items = collect($ids)->map(fn($id) => $models->get($id))->filter();

        return new LengthAwarePaginator(
            $items,
            $idPage->total(),
            $idPage->perPage(),
            $idPage->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
