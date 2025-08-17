<?php

namespace App\Application\Organization;

use App\Models\Organization;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrganizationService
{
    public function create(array $data): Organization
    {
        return DB::transaction(function () use ($data) {
            $payload = Arr::only($data, (new Organization)->getFillable());
            $organization = Organization::create($payload);

            if (!empty($data['phones'])) {
                $organization->phones()->createMany($this->normalizedPhones($data['phones']));
            }

            if (!empty($data['activity_ids'])) {
                $activityIds = array_values(array_unique($data['activity_ids']));
                $organization->activities()->sync($activityIds);
            }

            return $organization->load(['building', 'phones', 'activities']);
        });
    }

    public function update(Organization $organization, array $data): Organization
    {
        return DB::transaction(function () use ($organization, $data) {
            $payload = Arr::only($data, $organization->getFillable());
            if (!empty($payload)) {
                $organization->fill($payload)->save();
            }

            if (array_key_exists('phones', $data)) {
                $organization->phones()->delete();
                if (!empty($data['phones'])) {
                    $organization->phones()->createMany($this->normalizedPhones($data['phones']));
                }
            }

            if (array_key_exists('activity_ids', $data)) {
                $activityIds = !empty($data['activity_ids'])
                    ? array_values(array_unique($data['activity_ids']))
                    : [];
                $organization->activities()->sync($activityIds);
            }

            return $organization->load(['building', 'phones', 'activities']);
        });
    }

    public function delete(Organization $organization): void
    {
        $organization->delete();
    }

    private function normalizedPhones(array $rawPhones): array
    {
        $byNormalized = [];
        foreach ($rawPhones as $display) {
            $normalized = preg_replace('/\D+/', '', (string)$display) ?? '';
            if ($normalized === '') {
                continue;
            }
            $byNormalized[$normalized] = [
                'phone_display' => (string)$display,
                'phone_normalized' => $normalized,
            ];
        }
        return array_values($byNormalized);
    }
}
