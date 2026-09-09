<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'filename',
        'due_at',
        'priority',
        'status',
        'due_soon_alert_sent',
    ];
    
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
            'due_at' => 'datetime',
            'filename' => 'array',
            'due_soon_alert_sent' => 'boolean',
        ];
    }

    // Get the user that owns the task
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor: Always fetch due_at in Nepal Time
    public function getDueAtNepalAttribute()  // this creates due_at_nepal automatically i.e., getDueAtNepalAttribute() -> $task->due_at_nepal  as laravel converts due_at_nepal from snake_case to StudlyCase (DueAtNepal)
    {
        return $this->due_at ? $this->due_at->setTimezone('Asia/Kathmandu') : null;
    }
}
