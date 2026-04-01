<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PollToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'user_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateToken(): string
    {
        return hash('sha256', Str::random(64));
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at > now();
    }

    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }
}
