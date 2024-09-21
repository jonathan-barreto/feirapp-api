<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use Intervention\Image\Image;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Support\Facades\Storage;

// use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    //
    public function show($imageName)
    {
        $storagePath = storage_path('app/public/images/');
        $archivePath = $storagePath . $imageName;

        return response()->file($archivePath);
    }
}
