<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        $buildingIds = Building::query()->pluck('id')->all();
        $activityIds = Activity::query()->pluck('id')->all();

        if (empty($buildingIds) || empty($activityIds)) {
            $this->command?->warn('Нет зданий или деятельностей — пропускаю OrganizationSeeder');
            return;
        }

        for ($i = 0; $i < 60; $i++) {
            $org = Organization::create([
                'name' => $this->companyName($faker),
                'building_id' => $faker->randomElement($buildingIds),
            ]);

            $phones = $this->uniquePhones($faker, $faker->numberBetween(1, 3));
            foreach ($phones as $p) {
                $norm = preg_replace('/\D+/', '', $p);
                $org->phones()->create([
                    'phone_display' => $p,
                    'phone_normalized' => $norm,
                ]);
            }

            $attach = $faker->randomElements($activityIds, $faker->numberBetween(1, 3));
            $org->activities()->syncWithoutDetaching($attach);
        }
    }

    private function companyName($faker): string
    {
        return "{$faker->randomElement(['ООО', 'АО', 'ИП'])} «{$faker->company()}»";
    }

    private function uniquePhones($faker, int $count): array
    {
        $set = [];
        while (count($set) < $count) {
            $phone = $faker->numerify('+7 (9##) ###-##-##');
            $norm = preg_replace('/\D+/', '', $phone);
            $set[$norm] = $phone;
        }
        return array_values($set);
    }
}
