<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'question',
        'type',
        'required',
        'order',
    ];

    protected $casts = [
        'required' => 'boolean',
        'order' => 'integer',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollQuestionOption::class)->orderBy('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(PollResponse::class);
    }

    public function getAggregatedResults(): array
    {
        $responses = $this->responses;

        switch ($this->type) {
            case 'yes_no':
                return [
                    'yes' => $responses->where('text_response', 'yes')->count(),
                    'no' => $responses->where('text_response', 'no')->count(),
                    'total' => $responses->count(),
                ];

            case 'multiple_choice':
                $results = [];
                foreach ($this->options as $option) {
                    $results[$option->id] = [
                        'option_text' => $option->option_text,
                        'count' => $responses->where('poll_question_option_id', $option->id)->count(),
                    ];
                }
                $results['total'] = $responses->count();

                return $results;

            case 'text':
                return [
                    'responses' => $responses->pluck('text_response')->filter()->toArray(),
                    'total' => $responses->count(),
                ];

            case 'number':
                $numbers = $responses->pluck('number_response')->filter();

                return [
                    'average' => $numbers->avg(),
                    'min' => $numbers->min(),
                    'max' => $numbers->max(),
                    'count' => $numbers->count(),
                ];

            default:
                return [];
        }
    }
}
