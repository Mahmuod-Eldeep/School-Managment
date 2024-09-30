<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"title"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the task"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the task"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Task creation timestamp"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Task update timestamp"
 *     )
 * )
 */

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"task"},
     *     summary="Get all tasks",
     *     operationId="indexTask",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('is_done')
            ->defaultSort('created_at')
            ->allowedSorts(['title', 'is_done', 'created_at'])
            ->paginate();
        return new TaskCollection($tasks);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     tags={"task"},
     *     summary="Get a specific task",
     *     operationId="showTask",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(Request $request, Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"task"},
     *     summary="Create a new task",
     *     operationId="storeTask",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Submit the task data using the following form:
     *         <form id='taskForm'>
     *         <label for='title'>Task Title:</label>
     *         <input type='text' id='title' name='title' required placeholder='Enter task title'>
     *         <button type='submit'>Create Task</button>
     *         </form>",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Mathematics",
     *                     description="The title of the task."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Task created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $task = Auth::user()->tasks()->create($validated);
        return new TaskResource($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{task}",
     *     tags={"task"},
     *     summary="Update a specific task",
     *     operationId="updateTask",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID of the task to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Submit the updated task data using the following form:
     *         <form id='updateTaskForm'>
     *         <label for='title'>Task Title:</label>
     *         <input type='text' id='title' name='title' required placeholder='Enter updated task title'>
     *         <button type='submit'>Update Task</button>
     *         </form>",
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Mathematics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();
        $task->update($validated);
        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{task}",
     *     tags={"task"},
     *     summary="Delete a specific task",
     *     operationId="deleteTask",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
