<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadServiceTest extends TestCase
{
    public function test_pdf_s3_upload_and_exists_check(): void
    {
        $extension  = ".pdf";
        $name = Str::uuid()  . $extension;
        $file = UploadedFile::fake()->create($name, 1020);

        $file->storeAs("", $file->getClientOriginalName(), 's3');

        $status = Storage::disk('s3')->exists($name);

        $this->assertTrue($status);
    }
}
