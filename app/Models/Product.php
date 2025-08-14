<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'img_path',
        'category_id',
        'stock',
        'status',
    ];

    /**
     * Get the category associated with the product.
     */
    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }
}
