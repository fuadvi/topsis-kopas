<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectsRequest;
use App\Http\Traits\RespondFormatter;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use  RespondFormatter;
    public function index()
    {
        return $this->success('dropdown mata pelajaran',Subject::pluck('name','id'));
    }

    public function store(SubjectsRequest $request)
    {
       $subject = Subject::create($request->validated());

       return $this->success('berhasil membuat mata pelajaran', $subject);
    }

    public function show(Subject $subject)
    {
        return $this->success('detail mata pelajaran', $subject);
    }

    public function update(SubjectsRequest $request, Subject $subject)
    {
        $subject?->update($request->validated());
        return $this->success('berhasil merubah data mata pelajaran', null);
    }

    public function destroy(Subject $subject)
    {
        $name = $subject->name;
        $subject?->delete();
        return $this->success('berhasil menghapus mata pelajaran '.$name, null);
    }
}
