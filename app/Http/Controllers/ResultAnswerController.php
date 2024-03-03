<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetailAnswerRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\Answer;
use App\Models\JurusanPNL;
use Illuminate\Http\Request;

class ResultAnswerController extends Controller
{
    use RespondFormatter;

    public function __construct()
    {
//        $this->middleware('admin')->except(['detail','index']);
    }


    public function detail(DetailAnswerRequest $request)
    {
        $data = Answer::whereUserId($request->user()->id)
            ->whereType($request->metode)
            ->get();

        return $this->success("detail jawaban", $data);
    }

    public function index(Request $request)
    {
        $minat = Answer::whereUserId($request->user()->id)
            ->whereQuestionName('Tes Minat')
            ->get();

        $bakat = Answer::whereUserId($request->user()->id)
            ->whereQuestionName('Tes Bakat')
            ->get();

        return $this->success("detail jawaban keseluruhan", [
            'minat' => $minat,
            'bakat' => $bakat,
        ]);
    }

    public function pieChart()
    {
        $jurusan = JurusanPNL::withCount('result')
            ->get();

        return $this->success("list chart jurusan", [
            'jurusan' => $jurusan
        ]);

    }
}
