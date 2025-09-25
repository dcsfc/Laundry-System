<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Table uses columns: id, title, message, created_by, created_at (no updated_at)
    protected $fillable = ['title', 'message', 'created_by'];

    public $timestamps = false;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
