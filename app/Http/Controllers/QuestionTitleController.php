<?php

namespace App\Http\Controllers;

use App\Http\Traits\RespondFormatter;
use App\Models\QuestionTitle;
use Illuminate\Http\Request;

class QuestionTitleController extends Controller
{
    use RespondFormatter;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $this->success('list kategori soal', QuestionTitle::withCount('question')->get());
    }
}
