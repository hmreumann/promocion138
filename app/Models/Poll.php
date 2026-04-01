<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'published_at',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(PollQuestion::class)->orderBy('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(PollResponse::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(PollToken::class);
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function getTotalResponsesCount(): int
    {
        return $this->tokens()->whereNotNull('used_at')->count();
    }
}
