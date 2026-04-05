<?php

namespace App\Services;

use App\Models\Layer;
use App\Models\Status;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LayerPropagationService
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

        // -------------------------
        // 1. Update current layer
        // -------------------------
        if ($layer->children()->exists()) {

            $layer->update([
                'status_id' => $statusId
            ]);

        } else {

            $layer->update([
                'status_id' => $statusId,
                'progress_percent' => $isDone ? 100 : 0,
                'total_tasks' => $isCanceled ? 0 : 1,
                'completed_tasks' => $isDone ? 1 : 0
            ]);
        }

        // -------------------------
        // 2. Cascade DOWN (ONLY if DONE)
        // -------------------------
        if ($isDone) {
            $this->cascadeDown($layer, $statusId);
        }

        // -------------------------
        // 3. Bubble UP (for non-done or safety)
        // -------------------------
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
                : (int)round(($completedTasks / $totalTasks) * 100);

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

    protected function bubbleUpDates(?Layer $node): void
    {
        while ($node) {

            $stats = $node->children()
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->selectRaw('
                MIN(layers.start_time) as min_start_time,
                MAX(layers.end_time) as max_end_time
            ')
                ->first();

            if (!$stats) {
                break;
            }

            $childMinStart = $stats->min_start_time;
            $childMaxEnd   = $stats->max_end_time;

            $newStart = $node->start_time
                ? min($node->start_time, $childMinStart)
                : $childMinStart;

            $newEnd = $node->end_time
                ? max($node->end_time, $childMaxEnd)
                : $childMaxEnd;

            if (
                $node->start_time == $newStart &&
                $node->end_time == $newEnd
            ) {
                break;
            }

            $node->update([
                'start_time' => $newStart,
                'end_time'   => $newEnd,
            ]);

            $node = $node->parent;
        }
    }

    protected function cascadeDown(Layer $layer, int $statusId): void
    {
        $layer->descendants()
            ->with('status')
            ->get()
            ->each(function ($node) use ($statusId) {

                // skip canceled
                if ($node->status && $node->status->category === 'canceled') {
                    return;
                }

                $isLeaf = !$node->children()->exists();

                if ($isLeaf) {
                    // Leaf → full update
                    $node->update([
                        'status_id' => $statusId,
                        'progress_percent' => 100,
                        'total_tasks' => 1,
                        'completed_tasks' => 1
                    ]);

                    // propagate from leaf
                    $this->bubbleUp($node->parent);

                } else {
                    // Container → status only
                    $node->update([
                        'status_id' => $statusId
                    ]);
                }
            });
    }
}