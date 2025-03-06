<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Task API",
 *     description="Task API")
 */

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get list of tasks",
     *     @OA\Response(
     *         response=200,
     *         description="Task list")
     * )
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'id');

        $data = Task::query();

        if ($search) {
            $data->where('title', 'like', '%' . $search . '%');
        }

        $allowedSortFields = ['due_date', 'create_date'];
        if ($sort && in_array($sort, $allowedSortFields)) {
            $data->orderBy($sort);
        }

        $data = $data->paginate(10);

        return TaskResource::collection($data);
    }

    public function create()
    {
        $response = [
            'message' => 'Not supported'
        ];
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     @OA\Parameter(
     *         name="X-Requested-With",
     *         in="header",
     *         description="Ajax request",
     *         required=true,
     *         @OA\Schema(type="string", example="XMLHttpRequest")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Задача1", maxLength=255),
     *             @OA\Property(property="description", type="string", example="Задача1 описание"),
     *             @OA\Property(property="due_date", type="datetime", example="2025-01-20T15:00:00"),
     *             @OA\Property(property="create_date", type="datetime", example="2025-01-20T15:00:00"),
     *             @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="высокий"),
     *             @OA\Property(property="category", type="string", example="Работа"),
     *             @OA\Property(property="status", type="string", enum={"выполнена", "не выполнена"}, example="не выполнена"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $stored = Task::create($request->all());
        $response = [
            'id' => $stored->id,
            'message' => 'Task created successfully'
        ];
        return response()->json($response, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get task by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response=200, description="Task found"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function edit(Task $task)
    {
        $response = [
            'message' => 'Not supported'
        ];
        return response()->json($response, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update task by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Parameter(
     *         name="X-Requested-With",
     *         in="header",
     *         description="Ajax request",
     *         required=true,
     *         @OA\Schema(type="string", example="XMLHttpRequest")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Задача2", maxLength=255),
     *             @OA\Property(property="description", type="string", example="Задача2 описание обновленное"),
     *             @OA\Property(property="due_date", type="datetime", example="2025-01-25T18:00:00"),
     *             @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="высокий"),
     *             @OA\Property(property="status", type="string", enum={"выполнена", "не выполнена"}, example="не выполнена"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task updated"),
     *     @OA\Response(response=404, description="Task not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());
        $response = [
            'message' => 'Task updated successfully'
        ];
        return response()->json($response, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete task by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response=200, description="Task deleted"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);
        $response = [
            'message' => 'Task deleted successfully'
        ];
        return response()->json($response, 200);
    }
}
