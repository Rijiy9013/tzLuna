<?php

namespace App\Infrastructure\Persistence\Eloquent\Query;

use Illuminate\Support\Facades\DB;

final class ActivityTreeQueries
{
    public function descendantsIncludingSelf(string $rootId): array
    {
        $sql = <<<SQL
            with recursive sub(id) as (
              select id from activities where id = :root
              union all
              select a.id from activities a join sub s on a.parent_id = s.id
            )
            select id from sub
            SQL;
        return collect(DB::select($sql, ['root' => $rootId]))->pluck('id')->all();
    }
}
