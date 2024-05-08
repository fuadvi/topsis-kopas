<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Http\Traits\RespondFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    use RespondFormatter;
    /**
     * Handle the incoming request.
     */
    public function __invoke(UploadImageRequest $request)
    {
        $url = '';
        if ($request->has('image'))
        {
          $url =  url(Storage::url($request->file('image')?->store(
              "image",
              "public"
          )));
        }

        return $this->success("berhasil upload file",[
            "file" => $url
        ]);
    }
}
