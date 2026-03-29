<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = 'projects';

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function layers(): HasMany
    {
        return $this->hasMany(Layer::class);
    }

    public function rootLayers(): Project|HasMany
    {
        return $this->hasMany(Layer::class)
            ->whereNull('parent_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(Status::class);
    }

}