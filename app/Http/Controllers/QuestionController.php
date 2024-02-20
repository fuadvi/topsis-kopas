<?php

namespace App\Http\Controllers;

use App\Http\Traits\RespondFormatter;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use RespondFormatter;

    public function index(Request $request)
    {
        return $this->success('list soal',Question::with('choices')->get());
    }
}
