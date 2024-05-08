<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User; // استيراد النموذج User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    //----------------------------------------------------Function_To_Get_All_User-----------------------------------------------------------------------------------

    public function index()
    {
        $User = QueryBuilder::for(User::class)
            ->allowedIncludes('tasks')
            ->paginate();
        return new  UserCollection($User);
    }






    //----------------------------------------------------Function_To_Create_User-----------------------------------------------------------------------------------


    public function store(CreateUserRequest $request)
    {




        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'phoneNumber' => $request->phoneNumber,
            'password' =>   $request['password'] = Hash::make($request['password']),
            'classRoom' => $request->classRoom,
        ]);
        return response()->json(['message' => 'The User Create Succssefully'], 201);
    }
    //----------------------------------------------------Function_To_get_User-----------------------------------------------------------------------------------

    public function show(Request $request, User $user)
    {
        // $user_Data = Auth::user();
        // if ($user_Data->id === $user->id) {
        //     return new UserResource($user);
        // }
        // return response()->json(['massege:' => "this Action is Not Unauthorized"], 401);
        return new UserResource($user);
    }










    //----------------------------------------------------Function_To_Update_User-----------------------------------------------------------------------------------

    public function update(User $user, Request $request)
    {

        $user = User::find($user->id);
        if (!$user) {
            return response()->json(['message' => ' this user is not Found '], 404);
        }
        $user->update($request->all());
        return new UserResource($user);
    }


    //----------------------------------------------------Function_To_Delete_User-----------------------------------------------------------------------------------
    public function destroy(Request $request, User $user)
    {

        $user = User::find($user->id);
        if (!$user) {
            return response()->json(['message' => ' this user is not Found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User Deleted Successfully'], 200);
    }
}
