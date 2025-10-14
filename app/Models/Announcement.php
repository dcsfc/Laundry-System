<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message', 
        'type',
        'link',
        'visible_to',
        'expires_at',
        'is_pinned',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active announcements
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for visible to specific role
     */
    public function scopeVisibleTo($query, $role)
    {
        return $query->where(function($q) use ($role) {
            $q->where('visible_to', 'all')
              ->orWhere('visible_to', $role);
        });
    }

    /**
     * Scope for pinned announcements
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Get type badge colors
     */
    public function getTypeBadgeClassAttribute()
    {
        $colors = [
            'new' => 'bg-blue-600/20 text-blue-400 border-blue-600/30',
            'improvement' => 'bg-green-600/20 text-green-400 border-green-600/30',
            'fix' => 'bg-orange-600/20 text-orange-400 border-orange-600/30',
            'maintenance' => 'bg-yellow-600/20 text-yellow-400 border-yellow-600/30',
            'alert' => 'bg-red-600/20 text-red-400 border-red-600/30',
        ];

        return $colors[$this->type] ?? 'bg-gray-600/20 text-gray-400 border-gray-600/30';
    }

    /**
     * Get formatted type name
     */
    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->type);
    }

    /**
     * Check if announcement is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get short message (truncated)
     */
    public function getShortMessageAttribute()
    {
        return \Str::limit($this->message, 120);
    }
}
