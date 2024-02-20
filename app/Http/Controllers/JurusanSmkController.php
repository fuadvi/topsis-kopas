<?php

namespace App\Http\Controllers;

use App\Http\Traits\RespondFormatter;
use App\Models\JurusanSmk;
use Illuminate\Http\Request;

class JurusanSmkController extends Controller
{
    use RespondFormatter;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $this->success('list jurusan smk',JurusanSmk::all('id','nama'));
    }
}
