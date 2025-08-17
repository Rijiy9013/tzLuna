<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'buildings';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['address'];

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
