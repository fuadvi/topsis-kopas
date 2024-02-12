<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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

        return $this->success('login successfuly',(object)[
            "token" => $user->createToken('token')->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
       $user = User::create($data);

       return $this->success('success register', [
           'name' => $user->nama
       ]);
    }


    public function logout(User $user)
    {
        $user->tokens()->delete();

        $this->success('success logout',null);
    }
}
