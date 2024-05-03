<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload(UploadFileRequest $request): JsonResponse
    {
        $file = $request->file('file');

        /**
         *  all exceptions while uploading: will be handled by App\Exceptions\Handler.php;
         */
        $file->store('', 's3');
        $file_url = Storage::disk('s3')->url($file->getClientOriginalName());

        File::query()->create([
            "filename" => $file->getClientOriginalName(),
            'url' => $file_url
        ]);

        return response()->json(["url" => $file_url]);
    }
}
