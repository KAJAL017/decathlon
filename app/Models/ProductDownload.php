<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDownload extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
