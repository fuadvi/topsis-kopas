<?php

namespace App\Http\Controllers;

use App\Http\Resources\PerhitunganRecource;
use App\Http\Resources\UserResource;
use App\Http\Traits\RespondFormatter;
use App\Models\Perhitungan;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use  RespondFormatter;

    public function show($id)
    {
        $user = User::with([
            'result' => fn($query) => $query->whereNot('metode', 'copras')->orderByDesc('id'),
            'jurusan'
        ])
            ->findOrFail($id);

        return $this->success('detail user', new UserResource($user));
    }

    public function index()
    {
        $user = User::with([
            'result' => fn($query) => $query->whereNot('metode', 'copras')->orderByDesc('id'),
        ])->get();

        return $this->success('list user', UserResource::collection($user));
    }

    public function perhitungan(Request $request, $userId, $position)
    {
        $perhitungan = Perhitungan::whereUserId($userId)
                                    ->wherePosition($position)
                                    ->whereQuestionName($request->question_name)
                                    ->first();

        return new PerhitunganRecource($perhitungan);
    }
}
