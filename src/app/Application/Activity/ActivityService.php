<?php

namespace App\Application\Activity;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ActivityService
{
    public function builderForList(): Builder
    {
        return Activity::query()
            ->with('children')
            ->orderBy('level')
            ->orderBy('name');
    }

    public function show(Activity $activity): Activity
    {
        return $activity->load('children');
    }

    public function create(array $data): Activity
    {
        $parent = !empty($data['parent_id']) ? Activity::findOrFail($data['parent_id']) : null;
        $level = $parent ? ($parent->level + 1) : 1;
        if ($level > 3) {
            throw ValidationException::withMessages(['level' => 'Max depth is 3.']);
        }

        $fillable = Arr::only($data, (new Activity)->getFillable());
        $payload = $fillable + ['level' => $level];

        return Activity::create($payload);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $newParentId = array_key_exists('parent_id', $data)
            ? ($data['parent_id'] ?: null)
            : $activity->parent_id;

        $newParent = $newParentId ? Activity::findOrFail($newParentId) : null;
        $newLevel = $newParent ? ($newParent->level + 1) : 1;
        if ($newLevel > 3) {
            throw ValidationException::withMessages(['level' => 'Max depth is 3.']);
        }

        $fillable = Arr::only($data, $activity->getFillable());
        $payload = $fillable + ['level' => $newLevel];

        $activity->fill($payload)->save();

        return $activity->refresh();
    }

    public function delete(Activity $activity): void
    {
        $activity->delete();
    }
}
