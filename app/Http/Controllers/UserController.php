<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Traits\RespondFormatter;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use  RespondFormatter;

    public function show($id)
    {
        $user = User::with(['result','jurusan'])
            ->findOrFail($id);

        return $this->success('detail user', new UserResource($user));
    }

    public function index()
    {
        $user = User::with('result')->get();
        return $this->success('list user', UserResource::collection($user));
    }
}
