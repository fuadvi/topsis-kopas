<?php

namespace App\Http\Controllers;

use App\Http\Requests\BobotSubjectRequest;
use App\Http\Requests\CriteriaJurusanRequest;
use App\Http\Requests\DeleteCriteriaJurusanRequest;
use App\Http\Requests\JurusanPnlRequest;
use App\Http\Resources\JurusanPnlResource;
use App\Http\Traits\RespondFormatter;
use App\Models\JurusanPNL;
use Illuminate\Http\JsonResponse;

class JurusanPnlController extends Controller
{
    use RespondFormatter;
    public function index(): JsonResponse
    {
        $jurusanPNL = JurusanPnlResource::collection(JurusanPNL::with(['criteria.criteria.subcriteria'])->get());

        return $this->success('list data jurusan pnl', $jurusanPNL);
    }

    public function store(JurusanPnlRequest $request): JsonResponse
    {
        $jurusanPNL = JurusanPNL::create($request->validated());

        return $this->success('list data jurusan pnl', $jurusanPNL);
    }

    public function show(JurusanPNL $jurusan_pnl): JsonResponse
    {
        return $this->success("detail jurusan {$jurusan_pnl->name}", $jurusan_pnl);
    }

    public function update(JurusanPnlRequest $request, JurusanPNL $jurusan_pnl): JsonResponse
    {
        $jurusan_pnl->update($request->validated());

        return $this->success("berhasil melakukan perubahan", $jurusan_pnl);
    }

    public function destroy(JurusanPNL $jurusan_pnl): JsonResponse
    {
        $jurusan_pnl->delete();

        return $this->success("berhasil menghapus jurusan", null);
    }

    public function addCriteriaJurusan(CriteriaJurusanRequest $request, JurusanPNL $jurusan_pnl): JsonResponse
    {
        $jurusan_pnl->load('criteria');

        $jurusan_pnl->criteria()->createMany($request->data);

        return $this->success("berhasil menambahkan criteria jurusan", null);
    }

    public function deleteCriteriaJurusan(DeleteCriteriaJurusanRequest $request, JurusanPNL $jurusan_pnl): JsonResponse
    {
        $jurusan_pnl->load('criteria');

        $jurusan_pnl->criteria
            ?->firstWhere("criteria_id",$request->criteria_id)
            ?->delete();

        return $this->success("berhasil menambahkan criteria jurusan", null);
    }

    public function addBobotSubjectJurusan(BobotSubjectRequest $request, JurusanPNL $jurusan_pnl): JsonResponse
    {
        $jurusan_pnl->load('subject');

        // hapus bobot subject jurusan
        $jurusan_pnl?->subject()?->delete();

        $jurusan_pnl->subject()->createMany($request->data);

        return $this->success("berhasil menambahkan criteria jurusan", null);
    }

}
