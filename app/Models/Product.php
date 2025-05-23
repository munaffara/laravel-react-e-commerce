<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    // media library
    use InteractsWithMedia;

   public function registerMediaConversions(?Media $media = null): void
   {
    $this->addMediaConversion('thumb')
        ->width(100);

    $this->addMediaConversion('small')
        ->width(480);
    $this->addMediaConversion('medium')
        ->width(800);
    $this->addMediaConversion('large')
        ->width(1200);
    // $this->addMediaConversion('original')
    //     ->width(1920);
   }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
