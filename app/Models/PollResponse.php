<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'user_id',
        'poll_question_id',
        'poll_question_option_id',
        'text_response',
        'number_response',
    ];

    protected $casts = [
        'number_response' => 'decimal:2',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pollQuestion(): BelongsTo
    {
        return $this->belongsTo(PollQuestion::class);
    }

    public function pollQuestionOption(): BelongsTo
    {
        return $this->belongsTo(PollQuestionOption::class);
    }
}
