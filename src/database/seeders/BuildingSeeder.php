<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        $lat0 = 55.751244;
        $lng0 = 37.618423;

        for ($i = 0; $i < 15; $i++) {
            $id = Str::uuid()->toString();
            $p = $this->randomPointNear($lat0, $lng0, 8000); // в радиусе 8 км

            DB::insert(
                "INSERT INTO buildings (id, address, location, created_at, updated_at)
                     VALUES (?, ?, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, NOW(), NOW())",
                [$id, $faker->address(), $p['lng'], $p['lat']]
            );
        }
    }

    private function randomPointNear(float $lat0, float $lng0, int $radiusMeters): array
    {
        $dist = mt_rand(0, $radiusMeters);
        $theta = mt_rand(0, 360) * M_PI / 180;

        $metersPerDegLat = 111320.0;
        $metersPerDegLng = 111320.0 * cos($lat0 * M_PI / 180.0);

        $dLat = ($dist * cos($theta)) / $metersPerDegLat;
        $dLng = ($dist * sin($theta)) / $metersPerDegLng;

        return ['lat' => $lat0 + $dLat, 'lng' => $lng0 + $dLng];
    }
}
