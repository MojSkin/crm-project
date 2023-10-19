<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;

class ProductCategory extends Model
{
    use HasFactory, HasPersianSlug;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'max_per_order',
        'max_per_day',
        'product_category_id',
        'priority',
        'is_self',
    ];

    protected $casts = [
        'is_self' => 'boolean'
    ];

    public function parent()
    {
        return $this->belongsTo(Product::class);
    }

    public function children()
    {
        return $this->hasMany(Product::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(150);
    }
}
