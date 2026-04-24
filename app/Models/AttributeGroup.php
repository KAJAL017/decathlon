<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AttributeGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id')->orderBy('sort_order');
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->slug) || trim($group->slug) === '') {
                $group->slug = static::generateUniqueSlug($group->name);
            }
        });

        static::updating(function ($group) {
            if ($group->isDirty('name')) {
                if (empty($group->slug) || trim($group->slug) === '') {
                    $group->slug = static::generateUniqueSlug($group->name, $group->id);
                }
            }
        });
    }

    protected static function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::slugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected static function slugExists($slug, $id = null)
    {
        $query = static::where('slug', $slug);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        return $query->exists();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
