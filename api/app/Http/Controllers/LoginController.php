<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        $attr = $request->validated();

        if (!Auth::attempt($attr, $request->remember)) {
            return $this->responseUnauthorized('Credentials do not match.');
        }

        $user = auth()->user();

        return $this->responseSuccess([
            'user' => UserResource::make($user),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->Zlogout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->responseNoContent();
    }
}
