<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CourseImageController extends Controller
{
    public function show(string $filename): BinaryFileResponse
    {
        if (!preg_match('/^[A-Za-z0-9._-]+$/', $filename)) {
            abort(404);
        }

        $path = public_path('upload/images/course/' . $filename);

        if (!is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
