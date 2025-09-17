<?php

namespace App\Helpers;

use Imagick;
use ImagickDraw;
use ImagickPixel;

class ImageHelper
{
    /**
     * Ģenerē MMA stilizētu attēlu ar virsrakstu
     */
    public static function generateMMAImage(string $title, string $path): string
    {
        $img = new Imagick();
        $width = 800;
        $height = 450;

        // fona krāsa
        $img->newImage($width, $height, new ImagickPixel('#111111'));

        $draw = new ImagickDraw();
        $draw->setFillColor('gold'); // Teksta krāsa
        $draw->setFont(public_path('fonts/Impact.ttf')); // Fonts TTF
        $draw->setFontSize(36);
        $draw->setGravity(Imagick::GRAVITY_CENTER);

        // Pievieno tekstu
        $img->annotateImage($draw, 0, 0, 0, $title);

        $img->setImageFormat('png');

        // Saglabā attēlu
        $img->writeImage($path);
        $img->clear();
        $img->destroy();

        return $path;
    }
}
