<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',
        'name',
        'slug',
        'price',
    ];

    /**
     * Set the slug attribute for the product.
     */
    public function setSlugAttribute($value)
    {
        $value .= '-'.substr(hash('xxh3', time()), 0, 5);
        $this->attributes['slug'] = Str::slug($value, '-');
    }
}
