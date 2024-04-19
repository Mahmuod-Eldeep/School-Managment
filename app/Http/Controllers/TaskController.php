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
 *     schema="task",
 *     title="task",
 *     description="task data",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="title", type="string", example="Mathematics"),
 *     @OA\Property(property="creator_id", type="integer", example="1"),
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
     *     summary="Get all task",
     *     operationId="indextask",
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/task")
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
            ->allowedFilters('is_Done')
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
     *     operationId="showtask",
     *     security={{"BearerAuth": {}}},
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
     *         @OA\JsonContent(ref="#/components/schemas/task")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="task not found"
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
     *     operationId="storetask",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="task data",
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Mathematics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="task created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(
     *             type="string",
     *             default="Bearer your_access_token_here"
     *         )
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
     *     operationId="updatetask",
     *     security={{"BearerAuth": {}}},
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
     *     @OA\RequestBody(
     *         required=true,
     *         description="task data",
     *         @OA\JsonContent(ref="#/components/schemas/task")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="task updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="task not found"
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
     *     operationId="deletetask",
     *     security={{"BearerAuth": {}}},
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
     *         description="task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="task not found"
     *     )
     * )
     */

    public function destroy(Request $request, Task $task)
    {

        $task->delete();
        return response()->noContent();
    }
}
