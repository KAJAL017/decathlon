<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSlugHistory extends Model
{
    protected $fillable = [
        'product_id',
        'old_slug',
        'new_slug',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
