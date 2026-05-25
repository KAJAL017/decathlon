<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailCampaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'subject', 'preview_text', 'type', 'status',
        'audience_type', 'template_id', 'content',
        'from_name', 'from_email', 'reply_to',
        'scheduled_at', 'sent_at',
        'total_recipients', 'sent_count', 'opened_count', 'clicked_count',
        'unsubscribed_count', 'bounced_count',
        'tags', 'created_by',
    ];

    protected $casts = [
        'tags'         => 'array',
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    // ── Computed Attributes ──────────────────────────────────────

    public function getOpenRateAttribute(): string
    {
        if (!$this->sent_count || $this->sent_count <= 0) return '0.0%';
        return round(($this->opened_count / $this->sent_count) * 100, 1) . '%';
    }

    public function getClickRateAttribute(): string
    {
        if (!$this->sent_count || $this->sent_count <= 0) return '0.0%';
        return round(($this->clicked_count / $this->sent_count) * 100, 1) . '%';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'      => 'gray',
            'scheduled'  => 'yellow',
            'sending'    => 'blue',
            'sent'       => 'green',
            'paused'     => 'orange',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // ── Relationships ────────────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
