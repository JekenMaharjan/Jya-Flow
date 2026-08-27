<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file_path',
        'due_at',
        'priority',
        'status',
        'is_completed',
    ];

    // Get the user that owns the task
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
