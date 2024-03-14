<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $this->responseSuccess(UserResource::collection($users));
    }

    /**
     * Return user resource
     *
     * @param Request $requset
     * @return \Illuminate\Http\Response
     */
    public function getAuthUser(Request $requset)
    {
        $user = auth()->user();
        $userResource = null;
        if ($user) {
            $userResource = UserResource::make($user);
        }

        return $this->responseSuccess($userResource);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->responseNotFound();
        }
        return $this->responseSuccess(UserResource::make($user));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return $this->responseCreated(UserResource::make($user));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->responseNotFound();
        }
        $user->update($request->validated());

        return $this->responseSuccess(UserResource::make($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->responseNotFound();
        }
        $user->delete();
        
        return $this->responseNoContent();
    }
}
