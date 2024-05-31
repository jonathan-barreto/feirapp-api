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
       
        $directory = public_path('storage/images');

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $name = $file->getFilename();

            $imagePath = public_path("storage\images\\$name");

            $manager = new ImageManager(Driver::class);

            $readImage = $manager->read($imagePath);

            $readImage->resize(300, 300);

            $readImage->save(public_path("storage\\resize\\$name"), 10);
        }
    }
}
