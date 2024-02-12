<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
 use RespondFormatter;
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('The provided credentials are incorrect.',422);
        }

        return $this->success('login successfuly',$user->createToken('token')->plainTextToken);
    }

    public function show(string $id)
    {
        //
    }

    public function register(Request $request)
    {
        //
    }


    public function logout(string $id)
    {
        //
    }
}
