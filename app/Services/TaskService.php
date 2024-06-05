<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TaskService
{
    public function getAllTasks(array $filters): Collection
    {
        return Task::when(isset($filters['status']), function (Builder $query) use ($filters) {
            $query->where('status', $filters['status']);
        })->when(isset($filters['due_date']), function (Builder $query) use ($filters) {
            $query->whereDate('due_date', $filters['due_date']);
        })->get();
    }

    public function createTask(array $data): Model
    {
        return Task::create($data);
    }

    public function getTaskById(int $id): ?Model
    {
        return Task::findOrFail($id);
    }

    public function updateTask(int $id, array $data): Model
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask(int $id): void
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
