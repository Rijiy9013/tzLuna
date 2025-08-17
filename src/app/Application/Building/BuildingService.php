<?php

namespace App\Application\Building;

use App\Models\Building;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BuildingService
{
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        return Building::query()->paginate($perPage);
    }

    public function show(Building $building): Building
    {
        return $building;
    }

    public function create(array $data): Building
    {
        $b = Building::create(['address' => $data['address']]);

        DB::statement(
            'UPDATE buildings SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography WHERE id = ?',
            [(float)$data['lng'], (float)$data['lat'], $b->id]
        );

        return $b->refresh();
    }

    public function update(Building $building, array $data): Building
    {
        // адрес
        if (array_key_exists('address', $data)) {
            $building->update(['address' => $data['address']]);
        }

        // координаты — только если пришли ОДНОВРЕМЕННО lat и lng
        $hasLat = array_key_exists('lat', $data);
        $hasLng = array_key_exists('lng', $data);
        if ($hasLat && $hasLng) {
            DB::statement(
                'UPDATE buildings SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography WHERE id = ?',
                [(float)$data['lng'], (float)$data['lat'], $building->id]
            );
        }

        return $building->refresh();
    }

    public function delete(Building $building): void
    {
        $building->delete();
    }
}
