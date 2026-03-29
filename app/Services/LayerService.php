<?php

namespace App\Services;

use App\Models\Layer;
use App\Models\Status;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class LayerService
{
    public function __construct(
        protected LayerStatusUpdateService $statusService
    ) {}

    /**
     * Change task status
     * @throws Exception
     */
    public function changeStatus(Layer $layer, int $statusId): void
    {
        $this->statusService->updateTaskStatus($layer, $statusId);
    }

    /**
     * Delete a layer only if it has no children.
     *
     * @throws Exception
     */
    public function deleteLayer(Layer $layer): void
    {
        if ($layer->children()->exists()) {
            throw new Exception(
                "Cannot delete this layer because it still contains child items."
            );
        }

        $parent = $layer->parent;

        $layer->delete();

        // Recalculate parent progress
        if ($parent) {
            $this->statusService->calculate($parent);
        }
    }

    /**
     * Create layer
     * @throws Throwable
     */
    public function createLayer(array $data): Layer
    {
        return DB::transaction(function () use ($data) {
            $users = $data['users'] ?? [];

            $this->initializeProgress($data);

            $layer = $this->createNode($data);

            // assign users
            if (!empty($users)) {

                $attachData = [];

                foreach ($users as $userId) {
                    $attachData[$userId] = [
                        'assigned_by' => auth()->id(),
                        'assigned_at' => now(),
                    ];
                }

                $layer->users()->attach($attachData);
            }

            if ($layer->parent) {
                if ($layer->parent->total_tasks > 0) {
                    $layer->parent->update([
                        'total_tasks' => 0,
                        'completed_tasks' => 0,
                        'progress_percent' => 0,
                    ]);
                }

                $this->statusService->calculate($layer->parent);
            }

            return $layer;
        });
    }

    /**
     * Update layer
     * @throws Throwable
     */
//    public function updateLayer(Layer $layer, array $data): Layer
//    {
//        return DB::transaction(function () use ($layer, $data) {
//            $users = $data['users'] ?? [];
//            unset($data['users']);
//
//            $oldParent = $layer->parent;
//
////            $statusChanged = isset($data['status_id']) && $data['status_id'] != $layer->status_id;
//            $parentChanged = isset($data['parent_id']) && $data['parent_id'] != $layer->parent_id;
//
//            $statusId = $data['status_id'] ?? null;
//            $statusChanged = isset($data['status_id']) && $data['status_id'] != $layer->status_id;
//
//            unset($data['status_id']);
//
//            if ($parentChanged) {
//
//                $newParent = !empty($data['parent_id'])
//                    ? Layer::findOrFail($data['parent_id'])
//                    : null;
//
//                if ($newParent && $newParent->isDescendantOf($layer)) {
//                    throw new Exception('Cannot move node inside its own subtree.');
//                }
//
//                unset($data['parent_id']);
//
//                $layer->fill($data);
//
//                if ($newParent) {
//                    $layer->appendToNode($newParent);
//                } else {
//                    $layer->makeRoot();
//                }
//
//                $layer->save();
//
//            } else {
//
//                $layer->update($data);
//
//            }
//
//            if ($parentChanged) {
//
//                if ($layer->parent) {
//                    $this->statusService->calculate($layer->parent);
//                }
//
//                if ($parentChanged && $oldParent) {
//                    $this->statusService->calculate($oldParent);
//                }
//            }
//
//            if ($statusChanged) {
//                $this->statusService->updateTaskStatus($layer, $statusId);
//            }
//
//            $assigner = auth()->id();
//            $now = now();
//
//            $syncData = [];
//
//            $existingAssignments = $layer->users->keyBy('id');
//
//            foreach ($users as $userId) {
//
//                $existing = $existingAssignments->get($userId);
//
//                $syncData[$userId] = [
//                    'assigned_by' => $assigner,
//                    'assigned_at' => $existing?->pivot->assigned_at ?? $now
//                ];
//            }
//
//            $layer->users()->sync($syncData);
//
//            return $layer;
//        });
//    }
    public function updateLayer(Layer $layer, array $data): Layer
    {
        return DB::transaction(function () use ($layer, $data) {

            $users = $data['users'] ?? [];
            unset($data['users']);

            $oldParent = $layer->parent;

            $parentChanged = isset($data['parent_id']) && $data['parent_id'] != $layer->parent_id;

            $statusId = $data['status_id'] ?? null;
            $statusChanged = isset($data['status_id']) && $data['status_id'] != $layer->status_id;

            // remove status from mass update (handled separately)
            unset($data['status_id']);

            // -------------------------
            // 1. Handle parent change (STRUCTURE ONLY)
            // -------------------------
            if ($parentChanged) {

                $newParent = !empty($data['parent_id'])
                    ? Layer::findOrFail($data['parent_id'])
                    : null;

                if ($newParent && $newParent->isDescendantOf($layer)) {
                    throw new Exception('Cannot move node inside its own subtree.');
                }

                unset($data['parent_id']);

                $layer->fill($data);

                if ($newParent) {
                    $layer->appendToNode($newParent);
                } else {
                    $layer->makeRoot();
                }

                // save new structure
                $layer->save();

                if ($layer->parent && $layer->parent->total_tasks > 0) {
                    $layer->parent->update([
                        'total_tasks' => 0,
                        'completed_tasks' => 0,
                        'progress_percent' => 0,
                    ]);
                }

            } else {
                $layer->update($data);
            }

            // -------------------------
            // 2. Handle status change (LEAF FIRST)
            // -------------------------
            if ($statusChanged) {
                $this->statusService->updateTaskStatus($layer, $statusId);
            }

            // -------------------------
            // 3. Handle parent recalculation (ONLY if no status change)
            // -------------------------
            if ($parentChanged && !$statusChanged) {

                if ($layer->parent) {
                    $this->statusService->calculate($layer->parent); // new parent
                }

                if ($oldParent) {
                    $this->statusService->calculate($oldParent); // old parent
                }
            }

            // -------------------------
            // 4. Sync users
            // -------------------------
            $assigner = auth()->id();
            $now = now();

            $syncData = [];
            $existingAssignments = $layer->users->keyBy('id');

            foreach ($users as $userId) {
                $existing = $existingAssignments->get($userId);

                $syncData[$userId] = [
                    'assigned_by' => $assigner,
                    'assigned_at' => $existing?->pivot->assigned_at ?? $now
                ];
            }

            $layer->users()->sync($syncData);

            return $layer;
        });
    }

    /**
     * Convert between task/container
     * @throws Throwable
     */
//    public function convertLayerType(Layer $layer, string $newType, ?int $statusId = null): Layer
//    {
//        return DB::transaction(function () use ($layer, $newType, $statusId) {
//
//            if ($layer->type === $newType) {
//                return $layer;
//            }
//
//            if ($newType === 'task') {
//
//                if ($layer->children()->exists()) {
//                    throw new Exception(
//                        "Cannot convert a folder to a task while it contains children."
//                    );
//                }
//
//                $progress = $this->resolveTaskProgress($statusId);
//
//                $layer->status_id = $statusId;
//                $layer->progress_percent = $progress;
//                $layer->total_tasks = 1;
//                $layer->completed_tasks = $progress === 100 ? 1 : 0;
//
//            } else {
//
//                $layer->status_id = null;
//                $layer->progress_percent = 0;
//                $layer->total_tasks = 0;
//                $layer->completed_tasks = 0;
//
//            }
//
//            $layer->type = $newType;
//            $layer->save();
//
//            $this->statusService->calculate($layer->parent);
//
//            return $layer;
//        });
//    }

    /**
     * Initialize progress values
     */
//    protected function initializeProgress(array &$data): void
//    {
//        $type = $data['type'] ?? 'container';
//
//        if ($type === 'task') {
//
//            $progress = $this->resolveTaskProgress($data['status_id'] ?? null);
//
//            $data['progress_percent'] = $progress;
//            $data['total_tasks'] = 1;
//            $data['completed_tasks'] = $progress === 100 ? 1 : 0;
//
//        } else {
//
//            $data['progress_percent'] = 0;
//            $data['status_id'] = null;
//            $data['total_tasks'] = 0;
//            $data['completed_tasks'] = 0;
//        }
//    }
    protected function initializeProgress(array &$data): void
    {
        $statusId = $data['status_id'] ?? null;

        $status = $statusId
            ? Status::select('category')->find($statusId)
            : null;

        $isDone = $status?->category === 'done';
        $isCanceled = $status?->category === 'canceled';

        $data['progress_percent'] = $isDone ? 100 : 0;
        $data['total_tasks'] = $isCanceled ? 0 : 1;
        $data['completed_tasks'] = $isDone ? 1 : 0;
    }

    /**
     * Create nestedset node
     */
    protected function createNode(array $data): Layer
    {
        if (!empty($data['parent_id'])) {

            $parent = Layer::findOrFail($data['parent_id']);

            unset($data['parent_id']);

            return $parent->children()->create($data);
        }

        return Layer::create($data);
    }

    /**
     * Resolve task progress from status
     */
//    protected function resolveTaskProgress(?int $statusId): int
//    {
//        if (!$statusId) {
//            return 0;
//        }
//
//        $status = Status::select('id','category')->find($statusId);
//
//        return ($status?->category === 'done') ? 100 : 0;
//    }
}