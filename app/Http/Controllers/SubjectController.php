<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectCollection;
use App\Http\Resources\SubjectResourse;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Schema(
 *     schema="Subject",
 *     title="Subject",
 *     description="Subject data",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="title", type="string", example="Mathematics"),
 *     @OA\Property(property="creator_id", type="integer", example="1"),
 * )
 */

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Subject::class, 'subject');
    }

    /**
     * @OA\Get(
     *     path="/api/subjects",
     *     tags={"Subjects"},
     *     summary="Get all subjects",
     *     operationId="indexSubjects",
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Subject")
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
        $Subject = QueryBuilder::for(Subject::class)
            ->allowedIncludes('tasks')
            ->paginate();
        return new SubjectCollection($Subject);
    }

    /**
     * @OA\Post(
     *     path="/api/subjects",
     *     tags={"Subjects"},
     *     summary="Create a new subject",
     *     operationId="storeSubject",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Subject data",
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Mathematics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subject created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Subject created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Subject")
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


    public function store(StoreSubjectRequest $request)
    {
        $validated = $request->validated();
        $Subject = Auth::user()->subjects()->create($validated);
        return new SubjectResourse($Subject);
    }

    /**
     * @OA\Get(
     *     path="/api/subjects/{subject}",
     *     tags={"Subjects"},
     *     summary="Get a specific subject",
     *     operationId="showSubject",
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="subject",
     *         in="path",
     *         description="ID of the subject",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Subject")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subject not found"
     *     )
     * )
     */

    public function show(Request $request, Subject $Subject)
    {
        return (new SubjectResourse($Subject))
            ->load('tasks');
    }

    /**
     * @OA\Put(
     *     path="/api/subjects/{subject}",
     *     tags={"Subjects"},
     *     summary="Update a specific subject",
     *     operationId="updateSubject",
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="subject",
     *         in="path",
     *         description="ID of the subject",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Subject data",
     *         @OA\JsonContent(ref="#/components/schemas/Subject")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subject updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Subject updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Subject")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subject not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     */

    public function update(UpdateSubjectRequest $request, Subject $Subject)
    {
        $validated = $request->validated();
        $Subject->update($validated);
        return new SubjectResourse($Subject);
    }

    /**
     * @OA\Delete(
     *     path="/api/subjects/{subject}",
     *     tags={"Subjects"},
     *     summary="Delete a specific subject",
     *     operationId="deleteSubject",
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="subject",
     *         in="path",
     *         description="ID of the subject",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Subject deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subject not found"
     *     )
     * )
     */

    public function destroy(Request $request, Subject $Subject)
    {
        $Subject->delete();

        return response()->noContent();
    }
}
