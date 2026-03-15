<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayerType extends Model
{
    protected $fillable = [
        'title',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function layers(): HasMany
    {
        return $this->hasMany(Layer::class);
    }
}
