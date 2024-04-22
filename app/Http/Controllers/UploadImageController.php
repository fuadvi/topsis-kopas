<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
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

        return $url;
    }
}
