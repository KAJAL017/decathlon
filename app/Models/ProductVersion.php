<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVersion extends Model
{
    const UPDATED_AT = null; // Only created_at, no updated_at

    protected $fillable = [
        'product_id',
        'user_id',
        'version_number',
        'change_type',
        'change_summary',
        'data_snapshot',
        'changes',
    ];

    protected $casts = [
        'data_snapshot' => 'array',
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('change_type', $type);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper Methods
    public static function createVersion($product, $changeType, $changeSummary = null, $changes = null)
    {
        // Get latest version number
        $latestVersion = static::where('product_id', $product->id)
            ->orderBy('version_number', 'desc')
            ->first();

        if ($latestVersion) {
            // Increment version
            $versionParts = explode('.', $latestVersion->version_number);
            $versionParts[count($versionParts) - 1]++;
            $newVersion = implode('.', $versionParts);
        } else {
            $newVersion = '1.0';
        }

        return static::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'version_number' => $newVersion,
            'change_type' => $changeType,
            'change_summary' => $changeSummary,
            'data_snapshot' => $product->toArray(),
            'changes' => $changes,
        ]);
    }

    public static function trackChanges($product, $changeType)
    {
        $changes = [];
        $dirty = $product->getDirty();

        foreach ($dirty as $field => $newValue) {
            $changes[$field] = [
                'old' => $product->getOriginal($field),
                'new' => $newValue,
            ];
        }

        $summary = static::generateChangeSummary($changeType, $changes);

        return static::createVersion($product, $changeType, $summary, $changes);
    }

    protected static function generateChangeSummary($changeType, $changes)
    {
        $fieldNames = array_keys($changes);
        
        if (empty($fieldNames)) {
            return ucfirst(str_replace('_', ' ', $changeType));
        }

        $readableFields = array_map(function($field) {
            return ucfirst(str_replace('_', ' ', $field));
        }, $fieldNames);

        return ucfirst(str_replace('_', ' ', $changeType)) . ': ' . implode(', ', $readableFields);
    }
}
