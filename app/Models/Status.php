<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = [
        'label',
        'description',
        'color',
        'category',
        'order',
        'project_id',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function layers(): HasMany
    {
        return $this->hasMany(Layer::class);
    }
}
