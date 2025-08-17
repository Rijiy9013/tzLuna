<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $mk = function (string $name, string $slug, ?Activity $parent = null, int $level = 1): Activity {
            return Activity::create([
                'name' => $name,
                'slug' => $slug,
                'parent_id' => $parent?->id,
                'level' => $level,
            ]);
        };

        // lvl 1
        $food = $mk('Еда', 'eda', null, 1);
        $auto = $mk('Автомобили', 'avto', null, 1);

        // lvl 2
        $meat = $mk('Мясная продукция', 'myaso', $food, 2);
        $milk = $mk('Молочная продукция', 'milk', $food, 2);

        $trucks = $mk('Грузовые', 'gruz', $auto, 2);
        $pass = $mk('Легковые', 'legk', $auto, 2);

        // lvl 3
        $mk('Запчасти', 'zapchasti', $pass, 3);
        $mk('Аксессуары', 'aksessuary', $pass, 3);
    }
}
