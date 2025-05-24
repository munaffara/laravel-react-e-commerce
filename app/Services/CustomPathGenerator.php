<?php

namespace App\Services;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath($media): string
    {
        // Generate a custom path based on the media model
        return md5($media->id . config('app.key')) . '/' ;
    }

    public function getPathForConversions($media): string
    {
        // Generate a custom path for conversions
         return md5($media->id . config('app.key')) . '/conversions/'; ;
    }

    public function getPathForResponsiveImages($media): string
    {
        // Generate a custom path for responsive images
        return md5($media->id . config('app.key')) . '/reponsive_images/'; ;
    }
}
