<?php

namespace App\Services;

use App\Models\Layer;
use App\Models\Status;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LayerStatusUpdateService
{
    /**
     * Update task status
     * @throws Exception
     */
    public function updateTaskStatus(Layer $layer, int $statusId): void
    {
        $status = Status::findOrFail($statusId);

        $isDone = $status->category === 'done';
        $isCanceled = $status->category === 'canceled';

        // 🔥 KEY FIX
        if ($layer->children()->exists()) {

            // DO NOT TOUCH total_tasks / completed_tasks
            $layer->update([
                'status_id' => $statusId,
                'progress_percent' => 0, // or leave unchanged
            ]);

        } else {

            // LEAF → behaves like task
            $layer->update([
                'status_id' => $statusId,
                'progress_percent' => $isDone ? 100 : 0,
                'total_tasks' => $isCanceled ? 0 : 1,
                'completed_tasks' => $isDone ? 1 : 0
            ]);
        }

        $this->bubbleUp($layer->parent);
    }

    /**
     * Public branch recalculation
     */
    public function calculate(?Layer $parent): void
    {
        $this->bubbleUp($parent);
    }

    /**
     * Bubble progress up the tree
     */
    protected function bubbleUp(?Layer $parent): void
    {
        while ($parent) {

            $stats = $parent->children()
                ->selectRaw('
                SUM(layers.total_tasks) as total_tasks,
                SUM(layers.completed_tasks) as completed_tasks
            ')->first();

            $totalTasks = $stats->total_tasks ?? 0;
            $completedTasks = $stats->completed_tasks ?? 0;

            $newProgress = $totalTasks === 0
                ? 0
                : (int) round(($completedTasks / $totalTasks) * 100);

            if (
                $parent->progress_percent === $newProgress &&
                $parent->total_tasks === $totalTasks &&
                $parent->completed_tasks === $completedTasks
            ) {
                break;
            }

            $parent->update([
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'progress_percent' => $newProgress
            ]);

            $parent = $parent->parent;
        }
    }
}