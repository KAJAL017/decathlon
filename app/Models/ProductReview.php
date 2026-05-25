<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id','reviewer_name','reviewer_email','rating','title','body',
        'status','verified_purchase','featured','images','helpful_count',
        'source','admin_reply','replied_at','replied_by','created_by',
    ];

    protected $casts = [
        'images'           => 'array',
        'verified_purchase'=> 'boolean',
        'featured'         => 'boolean',
        'replied_at'       => 'datetime',
    ];

    public function product()  { return $this->belongsTo(Product::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function replier()  { return $this->belongsTo(User::class, 'replied_by'); }

    public function getStarsAttribute(): string {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopeFeatured($q) { return $q->where('featured', true); }
}
