<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetailAnswerRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\Answer;
use App\Models\JurusanPNL;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultAnswerController extends Controller
{
    use RespondFormatter;

    public function __construct()
    {
        $this->middleware('admin')->except(['detail','index','pieChart']);
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
        $result = Answer::whereUserId($request->user()->id)
            ->groupBy('jurusan') // Mengelompokkan berdasarkan jurusan
            ->selectRaw('jurusan, sum(score) as total_score') // Menjumlahkan skor untuk setiap jurusan
            ->orderBy('total_score', 'desc') // Mengurutkan hasil berdasarkan total skor secara menurun
            ->get();

       $topRankData = collect($result)->first();

       $jurusanId = JurusanPNL::whereName($topRankData->jurusan)->value('id');

        Result::updateOrCreate(
            [
                'jurusan_pnl_id' => $jurusanId,
                'user_id' => $request->user()->id,
                'metode' => 'all',
            ],
           [
               "jurusan" => $topRankData->jurusan,
               'jurusan_pnl_id' => $jurusanId,
               'user_id' => $request->user()->id,
               'metode' => 'all',
           ]
        );

        return $this->success("detail jawaban keseluruhan", [
            'result' => $result
        ]);
    }

    public function pieChart(Request $request)
    {
        $jurusan = JurusanPNL::withCount([
            'result' =>  fn($query) => $query->whereMetode($request?->metode?? "all")
        ]) ->get();

        return $this->success("list chart jurusan", [
            'jurusan' => $jurusan
        ]);

    }
}
