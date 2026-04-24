<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImportExportJob extends Model
{
    protected $fillable = [
        'store_id',
        'user_id',
        'type',
        'format',
        'status',
        'file_name',
        'file_path',
        'download_url',
        'total_records',
        'processed_records',
        'successful_records',
        'failed_records',
        'settings',
        'field_mapping',
        'errors',
        'notes',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'field_mapping' => 'array',
        'errors' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeImports($query)
    {
        return $query->where('type', 'import');
    }

    public function scopeExports($query)
    {
        return $query->where('type', 'export');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Accessors
    public function getProgressPercentageAttribute()
    {
        if ($this->total_records == 0) {
            return 0;
        }
        
        return round(($this->processed_records / $this->total_records) * 100, 2);
    }

    public function getSuccessRateAttribute()
    {
        if ($this->processed_records == 0) {
            return 0;
        }
        
        return round(($this->successful_records / $this->processed_records) * 100, 2);
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at) {
            return null;
        }
        
        $endTime = $this->completed_at ?: now();
        return $this->started_at->diffInSeconds($endTime);
    }

    public function getFormattedDurationAttribute()
    {
        $duration = $this->duration;
        
        if (!$duration) {
            return 'Not started';
        }
        
        if ($duration < 60) {
            return $duration . ' seconds';
        } elseif ($duration < 3600) {
            return round($duration / 60, 1) . ' minutes';
        } else {
            return round($duration / 3600, 1) . ' hours';
        }
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'processing' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            'cancelled' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700'
        };
    }

    // Methods
    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed($errors = null)
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now(),
            'errors' => $errors,
        ]);
    }

    public function updateProgress($processed, $successful, $failed, $errors = null)
    {
        $this->update([
            'processed_records' => $processed,
            'successful_records' => $successful,
            'failed_records' => $failed,
            'errors' => $errors,
        ]);
    }

    public function getFileUrl()
    {
        if (!$this->file_path) {
            return null;
        }
        
        return Storage::url($this->file_path);
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($job) {
            $job->deleteFile();
        });
    }
}