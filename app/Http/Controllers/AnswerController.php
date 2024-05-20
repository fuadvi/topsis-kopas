<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\Answer;
use App\Models\BobotCriteria;
use App\Models\JurusanPNL;
use App\Models\Question;
use App\Models\Result;

class AnswerController extends Controller
{
    use RespondFormatter;
    /**
     * Handle the incoming request.
     */
    public function __invoke(AnswerRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $listCeteria = [];

        if (!$request->isRaport)
        {
            array_map(static function ($soal) use (&$listCeteria){
                $column = array_column($listCeteria, 'criteria_id');
                $cek = in_array($soal['criteria_id'], $column, true);

                if (!$cek) {
                    $listCeteria[] = [
                        'question_id' => $soal['question_id'],
                        'user_id' => $soal['user_id'],
                        'criteria_id' => $soal['criteria_id'],
                        'point' => $soal['point'],
                    ];
                } else {
                    $posisi = array_search($soal['criteria_id'], $column, true);
                    $listCeteria[$posisi]['point'] += $soal['point'];
                }
            }, $data['data']);
        } else
        {
            $listCeteria =  $data['data'];
        }

         $result = match ($request->metode)
         {
             "topsis" => $this->metodeTopsis($listCeteria,$request->metode),
             "copras" => $this->metodeCOPRAS($listCeteria,$request->metode)
         };

        return $this->success('successfully answered the question',collect(array_values($result))->sortBy('score',descending: true)->values());
    }

    public function metodeTopsis($listCeteria,$metode): array
    {
        $points = collect($listCeteria)->pluck('point');
        $userId = collect($listCeteria)->value('user_id');
        $questionId = collect($listCeteria)->value('question_id');

       $questionName = Question::with('title')
            ->findOrFail($questionId)
            ?->title
            ?->name;

        Answer::whereUserId($userId)
            ->whereType($metode)
            ->whereQuestionName($questionName)
            ?->delete();

        $alternatives  = JurusanPNL::with('criteria')->get();

        // Hitung matriks keputusan
        $decisionMatrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($listCeteria as $ceteria) {
                $row["point"][] = (object)[
                    "nilai" =>  $ceteria['point'],
                    "criteria_id" =>  $ceteria['criteria_id'] ?? 0,
                    "subject_id" =>  $ceteria['subject_id'] ?? 0
                ];
                $row["jurusan_id"] = $alternative->id;
            }
            $decisionMatrix[] = $row;
        }

        // Menghitung total dari setiap indeks
        $pembagi = array_reduce($decisionMatrix, static function ($carry, $item) {
            foreach ($item['point'] as $index => $value) {
                $carry[$index] += $value->nilai ** 2;
            }
            return $carry;
        }, array_fill(0, count($decisionMatrix[0]['point']), 0));

        // Normalisasi matriks keputusan
        $normalizedMatrix = [];
        foreach ($decisionMatrix as $row) {
            $index = 0;
            $normalizedRow = array_map(static function ($x) use ($pembagi,&$index) {
                $data = (object)[
                    "nilai" => $x->nilai / sqrt($pembagi[$index]),
                    "criteria_id" => $x->criteria_id,
                    "subject_id" => $x->subject_id
                ];
                $index++;
                return $data;
            }, $row['point']);

            $data = [
                "nilai" => $normalizedRow,
//                "criteria_id" => $row['criteria_id'] ?? 0,
//                "subject_id" => $row['subject_id'] ?? 0,
                "jurusan_id" => $row['jurusan_id'],
            ];
            $normalizedMatrix[] = $data;

        }

        // Hitung matriks terbobot
        $weightedMatrix = [];
        foreach ($normalizedMatrix as $row) {
            $weightedRow = [];

            foreach ($row['nilai'] as $key => $value) {
                $weightedRow[] = $value->nilai * $this->ambilBobotNilai(
                    $row['jurusan_id'],
                    $value->criteria_id,
                    $points[$key],
                    $value->subject_id,
                    );
            }

            $weightedMatrix[] = $weightedRow;
        }

        // Hitung solusi ideal positif (PIS) dan solusi ideal negatif (NIS)
        $numCriteria = count($listCeteria);
        $pis = $nis = array_fill(0, $numCriteria, 0);
        foreach ($weightedMatrix as $row) {
            foreach ($row as $key => $value) {
                $pis[$key] = max($pis[$key], $value);
                $nis[$key] = min($nis[$key], $value);
            }
        }

        // Hitung jarak dari setiap alternatif ke PIS dan NIS
        $positiveDistances = [];
        $negativeDistances = [];
        foreach ($weightedMatrix as $row) {
            $positiveDistance = $negativeDistance = 0;
            foreach ($row as $key => $value) {
                $positiveDistance += pow($value - $pis[$key], 2);
                $negativeDistance += pow($value - $nis[$key], 2);
            }
            $positiveDistances[] = sqrt($positiveDistance);
            $negativeDistances[] = sqrt($negativeDistance);
        }

        // Hitung skor TOPSIS
        $scores = [];
        foreach ($positiveDistances as $key => $positiveDistance) {
            if (($positiveDistance + $negativeDistances[$key]) ==0 or $negativeDistances[$key] == 0)
            {
                $nilai = 0;
            }else{
                $nilai = $negativeDistances[$key] / ($positiveDistance + $negativeDistances[$key]);
            }
            $scores[] = $nilai;
        }

        $result = [];
        foreach ($alternatives as $index => $alternative) {
            $data = [
                "jurusan" => $alternative->name,
                "jurusan_pnl_id" => $alternative->id,
                "score" => $scores[$index],
                "type" => $metode,
                "user_id" => $userId,
                "question_name" => $questionName,
                "metode" => $metode
            ];
            Answer::create($data);
            $result[]= $data;
        }

        Result::updateOrCreate(
            [
              'jurusan_pnl_id' => $result[0]['jurusan_pnl_id'],
              'user_id' => $result[0]['user_id'],
              'metode' => $result[0]['metode'],
            ],
            $result[0]
        );


        return $result;
    }

    public function metodeCOPRAS($listCriteria, $metode): array
    {
        $points = collect($listCriteria)->pluck('point');
        $userId = collect($listCriteria)->value('user_id');
        $questionId = collect($listCriteria)->value('question_id');

        $questionName = Question::with('title')
            ->findOrFail($questionId)
            ?->title
            ?->name;

        Answer::whereUserId($userId)
            ->whereType($metode)
            ->whereQuestionName($questionName)
            ?->delete();

        $alternatives  = JurusanPNL::with('criteria')->get();


        // Hitung matriks keputusan
        $decisionMatrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($listCriteria as $criteria) {
                $row["bobot"][] = (object)[
                  "nilai" =>  $criteria['point'],
                  "criteria_id" =>  $criteria['criteria_id'],
                  "subject_id" =>  $criteria['subject_id'] ?? 0
                ];
                $row["jurusan_id"] = $alternative->id;
            }
            $decisionMatrix[] = $row;
        }

        // Menghitung total dari setiap indeks
        $pembagi = array_reduce($decisionMatrix, static function ($carry, $item) {
            foreach ($item['bobot'] as $index => $value) {
                $carry[$index] += $value->nilai;
            }
            return $carry;
        }, array_fill(0, count($decisionMatrix[0]['bobot']), 0));

        // Normalisasi matriks keputusan
        $normalizedMatrix = [];
        foreach ($decisionMatrix as $rows) {
           $index = 0;
            $normalizedRow = array_map(static function ($x) use ($pembagi,&$index) {
                if ($x->nilai == 0)
                {
                    $nilai = 0;
                } else
                {
                    $nilai = $x->nilai / sqrt($pembagi[$index]);
                }

                $row = (object)[
                    "nilai" =>$nilai,
                    "criteria_id" => $x->criteria_id,
                    "subject_id" => $x->subject_id
                ];

                $index++;
                return $row;
            }, $rows['bobot']);

            $data = [
                "data" => $normalizedRow,
                "jurusan_id" => $rows['jurusan_id'],
            ];
            $normalizedMatrix[] = $data;
        }

        // Hitung skor COPRAS
        $scores = [];
        foreach ($normalizedMatrix as $row) {
            $score = 0;
            foreach ($row['data'] as $key => $value) {
                $score += $value->nilai * $this->ambilBobotNilai(
                    $row['jurusan_id'],
                    $value->criteria_id,
                    $points[$key],
                        $value->subject_id,
                    );
            }
            $scores[] = $score;
        }

        $result = [];
        foreach ($alternatives as $index => $alternative) {
            $data = [
                "jurusan" => $alternative->name,
                "jurusan_pnl_id" => $alternative->id,
                "score" => $scores[$index],
                "type" => $metode,
                "metode" => $metode,
                "user_id" => $userId,
                "question_name" => $questionName,
            ];

            Answer::create($data);
            $result[] = $data;
        }

        Result::updateOrCreate(
            [
                'jurusan_pnl_id' => $result[0]['jurusan_pnl_id'],
                'user_id' => $result[0]['user_id'],
                'metode' => $result[0]['metode'],
            ],
            $result[0]
        );

        return $result;
    }


    public function ambilBobotNilai($jurusanId, $criteriaId, $point, $subjectId): int
    {
       $jurusan =  JurusanPNL::with(['criteria','subject'])->findOrFail($jurusanId);

        if ($subjectId > 0)
        {
            return $this->bobotSubject($jurusan, $subjectId);
        }

        return $this->bobotCriteria($jurusan, $criteriaId, $point);
    }

    public function bobotSubject($jurusan, $subjectId): int
    {
        $bobotSubject = $jurusan->subject->where('subject_id',$subjectId)?->value('bobot');

        if (!$bobotSubject) {
            return 0;
        }

        return $bobotSubject;
    }

    public function bobotCriteria($jurusan, $criteriaId, $point): int
    {
        if ($jurusan->criteria->where('criteria_id',$criteriaId)->isEmpty()) {
            return 0;
        }

        return BobotCriteria::whereCriteriaId($criteriaId)
            ->where('range','>=',$point)
            ->value('point')?? 0;
    }

}
