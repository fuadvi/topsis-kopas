<?php

namespace App\Http\Controllers;

use App\Http\Requests\BobotCriteriaReqeust;
use App\Http\Requests\CriteriaRequest;
use App\Http\Resources\CriteriaResource;
use App\Http\Traits\RespondFormatter;
use App\Models\Criteria;

class CriteriaController extends Controller
{
    use RespondFormatter;

    public function dropDownCriteria()
    {
        return $this->success('list criterias', Criteria::pluck('name'));
    }
    public function index()
    {
        $criterias = Criteria::with('subcriteria')->get();

        return $this->success('list criterias', CriteriaResource::collection($criterias));
    }

    public function store(CriteriaRequest $request)
    {
        $criteria = Criteria::create($request->validated());

        return $this->success('berhasil create criteria', $criteria);
    }

    public function show(Criteria $criterion)
    {
        return $this->success('detail criteria', $criterion);
    }

    public function update(CriteriaRequest $request, Criteria $criterion)
    {
        $criterion?->update($request->validated());

        return $this->success('berhasil edit criteria', null);
    }

    public function destroy(Criteria $criterion)
    {
        $criterion?->delete();

        return $this->success('berhasil hapus criteria', null);
    }

    public function addBobotCriteria(BobotCriteriaReqeust $request ,Criteria $criterion)
    {
        $criterion->load('subcriteria');

        $criterion->subcriteria()->createMany($request->data);

        return $this->success('berhasil menambahkan bobot criteria', null);
    }

    public function removeBobotCriteria(Criteria $criterion, $bobotId)
    {
        $criterion->load('subcriteria');

        $criterion->subcriteria
            ?->find($bobotId)
            ?->delete();

        return $this->success('berhasil menghapus bobot criteria', null);
    }
}
