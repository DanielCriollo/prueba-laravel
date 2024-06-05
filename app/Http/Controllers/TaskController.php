<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class TaskController extends Controller
{


    public function __construct(private TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'due_date']);
        $tasks = $this->taskService->getAllTasks($filters);
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        return response()->json($task, 201);
    }

    public function show($id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);
        return response()->json($task);
    }

    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        return response()->json($task);
    }

    public function destroy($id): JsonResponse
    {
        $this->taskService->deleteTask($id);
        return response()->json(null, 204);
    }
}