<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCriteriaRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\SubCriteria;

class SubCriteriaController extends Controller
{
    use RespondFormatter;

    public function dropDown()
    {
        return $this->success('list subcriteria', SubCriteria::pluck('name','id'));
    }
    public function index()
    {
        $subCriteria = SubCriteria::all();
        return $this->success('list subcriteria', $subCriteria);
    }

    public function store(SubCriteriaRequest $request)
    {
        $subCriteria = SubCriteria::create($request->validated());
        return $this->success("berhasil membuat sub criteria {$subCriteria->name}", null);
    }

    public function show(SubCriteria $subCriteria)
    {
        return $this->success("subcriteria", $subCriteria);
    }

    public function update(SubCriteriaRequest $request, SubCriteria $subcriterion)
    {
        $subcriterion->update($request->validated());

        return $this->success("berhasil melakukan edit subcriteria", null);
    }

    public function destroy(SubCriteria $subcriterion)
    {
        $subcriterion->delete();

        return $this->success("subcriteria berhasil di hapus", null);
    }
}
