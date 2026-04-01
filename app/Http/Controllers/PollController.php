<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitPollRequest;
use App\Models\PollResponse;
use App\Models\PollToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PollController extends Controller
{
    public function show(string $token): View
    {
        $pollToken = PollToken::where('token', $token)->with(['poll.questions.options', 'user'])->firstOrFail();

        return view('polls.show', [
            'poll' => $pollToken->poll,
            'token' => $token,
            'user' => $pollToken->user,
        ]);
    }

    public function submit(SubmitPollRequest $request, string $token): RedirectResponse
    {
        $pollToken = PollToken::where('token', $token)->firstOrFail();
        $validated = $request->validated();

        foreach ($validated['responses'] as $questionId => $response) {
            $question = $pollToken->poll->questions()->findOrFail($questionId);

            $responseData = [
                'poll_id' => $pollToken->poll_id,
                'user_id' => $pollToken->user_id,
                'poll_question_id' => $questionId,
            ];

            switch ($question->type) {
                case 'yes_no':
                    $responseData['text_response'] = $response;
                    break;
                case 'multiple_choice':
                    $responseData['poll_question_option_id'] = $response;
                    break;
                case 'text':
                    $responseData['text_response'] = $response;
                    break;
                case 'number':
                    $responseData['number_response'] = $response;
                    break;
            }

            PollResponse::create($responseData);
        }

        $pollToken->markAsUsed();

        return redirect()->route('polls.thank-you', $token);
    }

    public function thankYou(string $token): View
    {
        $pollToken = PollToken::where('token', $token)->with('poll')->firstOrFail();

        return view('polls.thank-you', [
            'poll' => $pollToken->poll,
            'token' => $token,
        ]);
    }

    public function results(string $token): View
    {
        $pollToken = PollToken::where('token', $token)->with(['poll.questions.options'])->firstOrFail();
        $poll = $pollToken->poll;

        if ($poll->status !== 'published') {
            abort(404);
        }

        $totalResponses = $poll->getTotalResponsesCount();

        return view('polls.results', compact('poll', 'token', 'totalResponses'));
    }
}
