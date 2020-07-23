<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function resizeImage(string $imagePath, int $maxWidth = 1200)
    {
        $image = Image::make($imagePath);
        $width = $image->width();

        if ($width > $maxWidth) {
            $image->widen($maxWidth)->save($imagePath);
        }
    }


    protected function makeImageThumbs(string $imagePath, string $thumbsDir, array $widthSet = [])
    {
        $_widthSet = (!empty($widthSet)) ? $widthSet : [
            600, 300,
        ];

        $image = Image::make($imagePath);
        $width = $image->width();

        $pathParts = explode('/', $imagePath);
        $imageFile = array_pop($pathParts);
        $imageFileParts = explode('.', $imageFile);

        $imageName = $imageFileParts[0];
        $extension = $imageFileParts[1];

        $targetPath = implode('/', $pathParts) . '/thumbs';
        Storage::makeDirectory($thumbsDir);

        foreach ($_widthSet as $targetWidth) {

            if ($width > $targetWidth) {

                $resizedImageName = "{$imageName}_w_{$targetWidth}";
                $_image = $image->widen($targetWidth);
                $_image->save("{$targetPath}/{$resizedImageName}.{$extension}");
            }
        }
    }
}
