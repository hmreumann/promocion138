<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_question_id',
        'option_text',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function pollQuestion(): BelongsTo
    {
        return $this->belongsTo(PollQuestion::class);
    }
}
