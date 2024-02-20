<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use RespondFormatter;

    public function show(Request $request,$questionTitleId)
    {
        $data = Question::with('choices')
            ->whereQuestionTitleId($questionTitleId)
            ->get();
        return $this->success('list soal',$data);
    }

    public function store(QuestionRequest $request)
    {
        try {
            DB::beginTransaction();
            $data =$request->validated();
            $question = Question::create($data);

            $question->choices()->createMany($data['choices']);
            DB::commit();
        } catch (\Exception $err)
        {
            DB::rollBack();
            return $this->error($err->getMessage());
        }

        return $this->success('berhasil membuat soal',null);
    }

    public function update(QuestionRequest $request,$id)
    {
        $question = Question::findOrFail($id);

        try {
            DB::beginTransaction();
            $data =$request->validated();

           // hapus semua pilihan ganda
            $question->choices()->delete();

            // update data terbaru
            $question->update($data);
            $question->choices()->createMany($data['choices']);
            DB::commit();
        } catch (\Exception $err)
        {
            DB::rollBack();
            return $this->error($err->getMessage());
        }

        return $this->success('berhasil memperbarui soal',null);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        $question->choices()->delete();
        $question->delete();


        return $this->success('berhasil mengahpus soal',null);
    }
}
