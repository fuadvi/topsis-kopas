<?php

namespace App\Http\Controllers;

use App\Http\Traits\RespondFormatter;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    use RespondFormatter;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $this->success('list jurusan',Jurusan::all('id','nama'));
    }
}
