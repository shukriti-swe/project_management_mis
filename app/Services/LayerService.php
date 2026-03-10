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

            $this->initializeProgress($data);

            $layer = $this->createNode($data);

            if ($layer->parent) {
                $this->statusService->calculate($layer->parent);
            }

            return $layer;
        });
    }

    /**
     * Update layer
     * @throws Throwable
     */
    public function updateLayer(Layer $layer, array $data): Layer
    {
        return DB::transaction(function () use ($layer, $data) {

            $oldParent = $layer->parent;

            $statusChanged = isset($data['status_id']) && $data['status_id'] != $layer->status_id;
            $parentChanged = isset($data['parent_id']) && $data['parent_id'] != $layer->parent_id;

            if ($layer->type === 'task' && $statusChanged) {
                $this->statusService->updateTaskStatus($layer, $data['status_id']);
                unset($data['status_id']);
            }

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

                $layer->save();

            } else {

                $layer->update($data);

            }

            if ($parentChanged) {

                if ($layer->parent) {
                    $this->statusService->calculate($layer->parent);
                }

                if ($parentChanged && $oldParent) {
                    $this->statusService->calculate($oldParent);
                }
            }

            return $layer;
        });
    }

    /**
     * Convert between task/container
     * @throws Throwable
     */
    public function convertLayerType(Layer $layer, string $newType, ?int $statusId = null): Layer
    {
        return DB::transaction(function () use ($layer, $newType, $statusId) {

            if ($layer->type === $newType) {
                return $layer;
            }

            if ($newType === 'task') {

                if ($layer->children()->exists()) {
                    throw new Exception(
                        "Cannot convert a folder to a task while it contains children."
                    );
                }

                $progress = $this->resolveTaskProgress($statusId);

                $layer->status_id = $statusId;
                $layer->progress_percent = $progress;
                $layer->total_tasks = 1;
                $layer->completed_tasks = $progress === 100 ? 1 : 0;

            } else {

                $layer->status_id = null;
                $layer->progress_percent = 0;
                $layer->total_tasks = 0;
                $layer->completed_tasks = 0;

            }

            $layer->type = $newType;
            $layer->save();

            $this->statusService->calculate($layer->parent);

            return $layer;
        });
    }

    /**
     * Initialize progress values
     */
    protected function initializeProgress(array &$data): void
    {
        $type = $data['type'] ?? 'container';

        if ($type === 'task') {

            $progress = $this->resolveTaskProgress($data['status_id'] ?? null);

            $data['progress_percent'] = $progress;
            $data['total_tasks'] = 1;
            $data['completed_tasks'] = $progress === 100 ? 1 : 0;

        } else {

            $data['progress_percent'] = 0;
            $data['status_id'] = null;
            $data['total_tasks'] = 0;
            $data['completed_tasks'] = 0;
        }
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
    protected function resolveTaskProgress(?int $statusId): int
    {
        if (!$statusId) {
            return 0;
        }

        $status = Status::select('id','category')->find($statusId);

        return ($status?->category === 'done') ? 100 : 0;
    }
}