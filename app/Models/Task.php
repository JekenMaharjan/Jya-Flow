<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'due_at',
        'priority',
        'status',
    ];
    
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
            'due_at' => 'datetime',
        ];
    }

    // Get the user that owns the task
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
