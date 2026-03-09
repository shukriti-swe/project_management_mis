<?php

namespace App\Services;

use App\Models\Layer;
use App\Models\Status;

class LayerStatusUpdateService
{
    /**
     * Update task status
     */
    public function updateTaskStatus(Layer $layer, int $statusId): void
    {
        $status = Status::findOrFail($statusId);

        $layer->status_id = $statusId;
        $layer->progress_percent = ($status->category === 'done') ? 100 : 0;
        $layer->save();

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

            $children = $parent->children()
                ->select('id','type','status_id','progress_percent')
                ->with('status:id,category')
                ->get();

            $validChildren = $children->filter(function ($child) {

                if ($child->type === 'task') {
                    return optional($child->status)->category !== 'canceled';
                }

                return true;
            });

            $newProgress = $validChildren->isEmpty()
                ? 0
                : (int) round($validChildren->avg('progress_percent'));

            if ($parent->progress_percent === $newProgress) {
                break;
            }

            $parent->update([
                'progress_percent' => $newProgress
            ]);

            $parent = $parent->parent;
        }
    }
}