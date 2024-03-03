<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\Answer;
use App\Models\JurusanPNL;
use App\Models\Question;
use App\Models\Result;

class AnswerController extends Controller
{
    use RespondFormatter;
    /**
     * Handle the incoming request.
     */
    public function __invoke(AnswerRequest $request)
    {
        $data = $request->validated();
        $listCeteria = [];
         array_map(function ($soal) use (&$listCeteria){
             $column = array_column($listCeteria, 'criteria_id');
             $cek = in_array($soal['criteria_id'], $column, true);

             if (!$cek) {
                 $listCeteria[] = [
                     'question_id' => $soal['question_id'],
                     'user_id' => $soal['user_id'],
                     'criteria_id' => $soal['criteria_id'],
                     'point' => $soal['bobot'],
                 ];
             } else {
                 $posisi = array_search($soal['criteria_id'], $column, true);
                 $listCeteria[$posisi]['point'] += $soal['bobot'];
             }
        }, $data['data']);


         $result = match ($request->metode)
         {
             "topsis" => $this->metodeTopsis($listCeteria,$request->metode),
             "copras" => $this->metodeCOPRASDua($listCeteria,$request->metode)
         };

        return $this->success('successfully answered the question',collect($result)->sortBy('score',descending: true));
    }

    public function metodeTopsis($listCeteria,$metode)
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
                $row["point"][] = $ceteria['point'];
                $row["criteria_id"] = $ceteria['criteria_id'];
                $row["jurusan_id"] = $alternative->id;
            }
            $decisionMatrix[$alternative->name] = $row;
        }

        // Normalisasi matriks keputusan
        $normalizedMatrix = [];
        foreach ($decisionMatrix as $row) {
            $sumOfSquares = array_sum(array_map(function ($x) {
                return $x * $x;
            }, $row['point']));
            $sqrtSumOfSquares = sqrt($sumOfSquares);
            $normalizedRow = array_map(function ($x) use ($sqrtSumOfSquares) {
                return $x / $sqrtSumOfSquares;
            }, $row['point']);
            $data = [
                "nilai" => $normalizedRow,
                "criteria_id" => $row['criteria_id'],
                "jurusan_id" => $row['jurusan_id'],
            ];
            $normalizedMatrix[] = $data;

        }

        // Hitung matriks terbobot
        $weightedMatrix = [];
        foreach ($normalizedMatrix as $row) {
            $weightedRow = [];
            foreach ($row['nilai'] as $key => $value) {
                $weightedRow[] = $value * $this->ambilBobotNilai($row['jurusan_id'],$key+1,$points[$key]);
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
            $scores[] = $negativeDistances[$key] / ($positiveDistance + $negativeDistances[$key]);
        }

        $result = [];
        foreach ($alternatives as $index => $alternative) {
            $data = [
                "jurusan" => $alternative->name,
                "jurusan_pnl_id" => $alternative->id,
                "score" => $scores[$index],
                "type" => $metode,
                "user_id" => $userId,
                "question_name" => $questionName
            ];
            Answer::create($data);
            $result[]= $data;
        }

        Result::updateOrCreate(
            [
                'user_id' => $userId,
                'metode' => $metode
            ],
            $result[0]
        );


        return $result;
    }


    public function metodeCOPRAS($listCriteria,$metode)
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

        Answer::whereUserId($userId)
            ->whereType($metode)
            ?->delete();

        $alternatives  = JurusanPNL::with('criteria')->get();

            // Hitung matriks keputusan
        $decisionMatrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($listCriteria as $criteria) {
                $row["point"][] = $criteria['point'];
                $row["criteria_id"] = $criteria['criteria_id'];
                $row["jurusan_id"] = $alternative->id;
            }
            $decisionMatrix[$alternative->name] = $row;
        }

        // Normalisasi matriks keputusan
        $normalizedMatrix = [];
        foreach ($decisionMatrix as  $rows) {
            $sumOfSquares = array_sum(array_map(function ($x) {
                return $x * $x;
            }, $row['point']));
            $sqrtSumOfSquares = sqrt($sumOfSquares);
            $normalizedRow = array_map(function ($x) use ($sqrtSumOfSquares) {
                return $x / $sqrtSumOfSquares;
            }, $row['point']);
            $data = [
                "nilai" => $normalizedRow,
                "criteria_id" => $row['criteria_id'],
                "jurusan_id" => $row['jurusan_id'],
            ];
            $normalizedMatrix[] = $data;
        }
        // Hitung skor COPRAS
        $scores = [];
        foreach ($normalizedMatrix as $row) {
            $score = 0;
            foreach ($row['nilai'] as $key => $value) {
                $score += $value * $this->ambilBobotNilai($row['jurusan_id'], $row['criteria_id'], $points[$key]);
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
                "user_id" => $userId,
                "question_name" => $questionName,
            ];
            Answer::create($data);
            $result[] = $data;
        }

        Result::updateOrCreate(
            [
                'user_id' => $userId,
                'metode' => $metode
            ],
            $result[0]
        );

        return $result;
    }

    public function metodeCOPRASDua($listCriteria, $metode)
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

        Answer::whereUserId($userId)
            ->whereType($metode)
            ?->delete();

        $alternatives  = JurusanPNL::with('criteria')->get();


        // Hitung matriks keputusan
        $decisionMatrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($listCriteria as $criteria) {
                $row["bobot"][] = (object)[
                  "nilai" =>  $criteria['point'],
                  "criteria_id" =>  $criteria['criteria_id']
                ];
                $row["jurusan_id"] = $alternative->id;
            }
            $decisionMatrix[] = $row;
        }

        // Menghitung total dari setiap indeks
        $totals = array_reduce($decisionMatrix, function ($carry, $item) {
            foreach ($item['bobot'] as $index => $value) {
                $carry[$index] += $value->nilai;
            }
            return $carry;
        }, array_fill(0, count($decisionMatrix[0]['bobot']), 0));

        // Normalisasi matriks keputusan
        $normalizedMatrix = [];
        foreach ($decisionMatrix as $rows) {
           $index = 0;
            $normalizedRow = array_map(function ($x) use ($totals,&$index) {
                return (object)[
                    "nilai" => $x->nilai / $totals[$index],
                    "criteria_id" => $x->criteria_id
                ];
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
                $score += $value->nilai * $this->ambilBobotNilai($row['jurusan_id'], $value->criteria_id, $points[$key]);
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
                "user_id" => $userId,
                "question_name" => $questionName,
            ];

            Answer::create($data);
            $result[] = $data;
        }

        Result::updateOrCreate(
            [
                'user_id' => $userId,
                'metode' => $metode
            ],
            $result[0]
        );

        return $result;
    }


    public function ambilBobotNilai($jurusanId, $criteriaId, $point)
    {
       $jurusan =  JurusanPNL::with('criteria')->findOrFail($jurusanId);

        if ($jurusan->criteria->where('criteria_id',$criteriaId)->isEmpty()) return 0;

        return match ($criteriaId)
        {
            1 => $this->bobotPenalaranVisual($point),
            2 => $this->bobotPenalaranNumerik($point),
            3 => $this->bobotPenalaranUrutan($point),
            4 => $this->bobotPengenalanSpasial($point),
            5 => $this->bobotFiguralAngka($point),
            6 => $this->bobotSistematisasi($point),
            7 => $this->bobotTigaDimensi($point),
        };
    }

    public function bobotPenalaranVisual($point)
    {
       return match (true)
       {
         $point >= 20 => 5,
         $point  >=13 and $point <= 19 => 4,
         $point >=7 and $point <= 12 => 3,
         $point >=3 and $point <= 6 => 2,
           default => 1
       };
    }

    public function bobotPenalaranNumerik($point)
    {
        return match (true)
        {
            $point >= 14 => 5,
            $point  >=9 and $point <= 13 => 4,
            $point >=6 and $point <= 18 => 3,
            $point >=3 and $point <= 5 => 2,
            default => 1
        };
    }

    public function bobotPenalaranUrutan($point)
    {
        return match (true)
        {
            $point >= 16 => 5,
            $point  >=12 and $point <= 15 => 4,
            $point >=8 and $point <= 11 => 3,
            $point >=3 and $point <= 7 => 2,
            default => 1
        };
    }
    public function bobotPengenalanSpasial($point)
    {
        return match (true)
        {
            $point >= 43 => 5,
            $point  >=35 and $point <= 42 => 4,
            $point >=27 and $point <= 34 => 3,
            $point >=10 and $point <= 26 => 2,
            default => 1
        };
    }


    public function bobotFiguralAngka($point)
    {
        return match (true)
        {
            $point >= 22 => 5,
            $point  >=17 and $point <= 21 => 4,
            $point >=13 and $point <= 16 => 3,
            $point >=7 and $point <= 12 => 2,
            default => 1
        };
    }

    public function bobotSistematisasi($point)
    {
        return match (true)
        {
            $point >= 121 => 5,
            $point  >=91 and $point <= 120 => 4,
            $point >=61 and $point <= 90 => 3,
            $point >=31 and $point <= 60 => 2,
            default => 1
        };
    }

    public function bobotTigaDimensi($point)
    {
        return match (true)
        {
            $point >= 24 => 5,
            $point  >=18 and $point <= 23 => 4,
            $point >=12 and $point <= 17 => 3,
            $point >=6 and $point <= 11 => 2,
            default => 1
        };
    }

}
