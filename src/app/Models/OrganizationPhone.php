<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationPhone extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'organization_phones';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['organization_id', 'phone_display', 'phone_normalized'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
