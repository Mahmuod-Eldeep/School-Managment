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
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use a bearer token to access these endpoints",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="BearerAuth",
 * )
 */

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
     *   security={{"BearerAuth": {}}},
     *     operationId="indexSubjects",
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
     *     @OA\RequestBody(
     *         required=true,
     *         description="Submit the subject data using the following form:
     *         </form>",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Mathematics",
     *                     description="The title of the subject."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subject created successfully",
     *         @OA\JsonContent(
     *             type="object",
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
     *     @OA\Parameter(
     *         name="subject",
     *         in="path",
     *         description="ID of the subject to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Submit the updated subject data using the following form:
     *         <form id='updateSubjectForm'>
     *         <label for='title'>Subject Title:</label>
     *         <input type='text' id='title' name='title' required placeholder='Enter updated subject title'>
     *         <button type='submit'>Update Subject</button>
     *         </form>",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Mathematics",
     *                     description="The title of the subject."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subject updated successfully",
     *         @OA\JsonContent(
     *             type="object",
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
