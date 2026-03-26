<?php

namespace VisionPHP;

use VisionPHP\Core\Image;
use VisionPHP\Filters\Grayscale;
use VisionPHP\Filters\Blur;
use VisionPHP\Features\EdgeDetection;

class VisionPHP
{
    /**
     * Load an image from a file path.
     */
    public static function imread(string $filepath): Image
    {
        return new Image($filepath);
    }

    /**
     * Create a blank image.
     */
    public static function create(int $width, int $height): Image
    {
        $img = new Image();
        $img->createBlank($width, $height);
        return $img;
    }

    /**
     * Convert an image to Grayscale.
     */
    public static function cvtColor(Image $img, string $mode = 'GRAY'): Image
    {
        if (strtoupper($mode) === 'GRAY') {
            return Grayscale::apply($img);
        }
        throw new \Exception("Mode $mode not supported yet.");
    }

    /**
     * Apply Gaussian Blur to an image.
     */
    public static function GaussianBlur(Image $img, int $radius = 3): Image
    {
        return Blur::gaussian($img, $radius);
    }

    /**
     * Apply Sobel Edge Detection.
     */
    public static function Sobel(Image $img): Image
    {
        return EdgeDetection::sobel($img);
    }

    /**
     * Save an image to a file path.
     */
    public static function imwrite(string $filepath, Image $img): bool
    {
        return $img->save($filepath);
    }
}
