<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;

class Layer extends Model
{
    use NodeTrait;

    protected $fillable = [
        'name',
        'description',
        'status_id',
        'project_id',
        'progress_percent',
        'total_tasks',
        'completed_tasks',
        'type',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'progress_percent' => 'integer',
        'total_tasks' => 'integer',
        'completed_tasks' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function isTask(): bool
    {
        return $this->type === 'task';
    }
}
