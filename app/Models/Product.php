<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, HasPersianSlug;
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'max_per_order',
        'max_per_day',
        'min_stock',
        'product_category_id',
        'product_unit_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(150);
    }

}
