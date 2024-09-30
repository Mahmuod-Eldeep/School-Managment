<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     description="User resource",
 *     @OA\Property(property="id", type="integer", description="User ID"),
 *     @OA\Property(property="name", type="string", description="User name"),
 *     @OA\Property(property="email", type="string", description="User email"),
 *     @OA\Property(property="status", type="string", description="User status"),
 *     @OA\Property(property="phoneNumber", type="string", description="User phone number"),
 *     @OA\Property(property="classRoom", type="string", description="User class room"),
 *     @OA\Property(property="image_path", type="string", description="Image path")
 * )
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    //----------------------------------------------------Function_To_Get_All_User-----------------------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully fetched users",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     */
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedIncludes('tasks')
            ->paginate();
        return new UserCollection($users);
    }

    //----------------------------------------------------Function_To_Create_User-----------------------------------------------------------------------------------

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Name of the user"),
     *                 @OA\Property(property="email", type="string", format="email", description="User email"),
     *                 @OA\Property(property="status", type="string", description="User status"),
     *                 @OA\Property(property="phoneNumber", type="string", description="User phone number"),
     *                 @OA\Property(property="password", type="string", description="User password"),
     *                 @OA\Property(property="classRoom", type="string", description="User class room"),
     *                 @OA\Property(property="image", type="file", description="User profile image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     */
    public function store(CreateUserRequest $request)
    {
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'phoneNumber' => $request->phoneNumber,
            'password' => Hash::make($request['password']),
            'classRoom' => $request->classRoom,
            'image_path' => $imagePath,
        ]);

        return response()->json(['message' => 'The User was created successfully'], 201);
    }

    //----------------------------------------------------Function_To_Get_User-----------------------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Get user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    //----------------------------------------------------Function_To_Update_User-----------------------------------------------------------------------------------

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Update user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Name of the user"),
     *                 @OA\Property(property="email", type="string", format="email", description="User email"),
     *                 @OA\Property(property="status", type="string", description="User status"),
     *                 @OA\Property(property="phoneNumber", type="string", description="User phone number"),
     *                 @OA\Property(property="password", type="string", description="User password"),
     *                 @OA\Property(property="classRoom", type="string", description="User class room"),
     *                 @OA\Property(property="image", type="file", description="User profile image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return new UserResource($user);
    }

    //----------------------------------------------------Function_To_Delete_User-----------------------------------------------------------------------------------

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Delete user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
