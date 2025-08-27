<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'invoice_date',
        'due_date',
        'status',
        'billing_period',
        'description',
        'paid_at',
        'receipt_path',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, ['pending', 'waiting_review']) && $this->due_date < now()->toDateString();
    }

    public function isWaitingReview(): bool
    {
        return $this->status === 'waiting_review';
    }

    public function markAsWaitingReview(): void
    {
        $this->update([
            'status' => 'waiting_review',
        ]);
    }
}
