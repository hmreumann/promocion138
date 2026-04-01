<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePollRequest;
use App\Http\Requests\Admin\UpdatePollRequest;
use App\Jobs\SendPollInvitations;
use App\Models\Poll;
use App\Models\PollQuestion;
use App\Models\PollQuestionOption;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PollController extends Controller
{
    public function index(Request $request): View
    {
        $query = Poll::query()->withCount(['questions', 'tokens as responses_count' => function ($query) {
            $query->whereNotNull('used_at');
        }]);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $polls = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.polls.index', compact('polls'));
    }

    public function create(): View
    {
        return view('admin.polls.create');
    }

    public function store(StorePollRequest $request): RedirectResponse
    {
        $poll = Poll::create($request->validated());

        return redirect()->route('admin.polls.edit', $poll)->with('success', 'Encuesta creada exitosamente.');
    }

    public function edit(Poll $poll): View
    {
        $poll->load(['questions.options']);

        return view('admin.polls.edit', compact('poll'));
    }

    public function update(UpdatePollRequest $request, Poll $poll): RedirectResponse
    {
        $validated = $request->validated();

        // Update poll details
        $poll->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        // Handle questions
        if (isset($validated['questions'])) {
            // Get existing question IDs
            $existingQuestionIds = $poll->questions->pluck('id')->toArray();
            $submittedQuestionIds = [];

            foreach ($validated['questions'] as $questionData) {
                if (isset($questionData['id'])) {
                    // Update existing question
                    $question = PollQuestion::find($questionData['id']);
                    if ($question && $question->poll_id === $poll->id) {
                        $question->update([
                            'question' => $questionData['question'],
                            'type' => $questionData['type'],
                            'required' => $questionData['required'] ?? true,
                            'order' => $questionData['order'],
                        ]);
                        $submittedQuestionIds[] = $question->id;

                        // Handle options for multiple_choice
                        if ($questionData['type'] === 'multiple_choice' && isset($questionData['options'])) {
                            $existingOptionIds = $question->options->pluck('id')->toArray();
                            $submittedOptionIds = [];

                            foreach ($questionData['options'] as $optionData) {
                                if (isset($optionData['id'])) {
                                    $option = PollQuestionOption::find($optionData['id']);
                                    if ($option && $option->poll_question_id === $question->id) {
                                        $option->update([
                                            'option_text' => $optionData['option_text'],
                                            'order' => $optionData['order'],
                                        ]);
                                        $submittedOptionIds[] = $option->id;
                                    }
                                } else {
                                    // Create new option
                                    $newOption = $question->options()->create([
                                        'option_text' => $optionData['option_text'],
                                        'order' => $optionData['order'],
                                    ]);
                                    $submittedOptionIds[] = $newOption->id;
                                }
                            }

                            // Delete options not in submission
                            $optionsToDelete = array_diff($existingOptionIds, $submittedOptionIds);
                            PollQuestionOption::whereIn('id', $optionsToDelete)->delete();
                        }
                    }
                } else {
                    // Create new question
                    $question = $poll->questions()->create([
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'required' => $questionData['required'] ?? true,
                        'order' => $questionData['order'],
                    ]);
                    $submittedQuestionIds[] = $question->id;

                    // Create options if multiple_choice
                    if ($questionData['type'] === 'multiple_choice' && isset($questionData['options'])) {
                        foreach ($questionData['options'] as $optionData) {
                            $question->options()->create([
                                'option_text' => $optionData['option_text'],
                                'order' => $optionData['order'],
                            ]);
                        }
                    }
                }
            }

            // Delete questions not in submission
            $questionsToDelete = array_diff($existingQuestionIds, $submittedQuestionIds);
            PollQuestion::whereIn('id', $questionsToDelete)->delete();
        }

        return redirect()->route('admin.polls.edit', $poll)->with('success', 'Encuesta actualizada exitosamente.');
    }

    public function destroy(Poll $poll): RedirectResponse
    {
        if ($poll->status === 'published') {
            return redirect()->route('admin.polls.index')->with('error', 'No se puede eliminar una encuesta publicada.');
        }

        $poll->delete();

        return redirect()->route('admin.polls.index')->with('success', 'Encuesta eliminada exitosamente.');
    }

    public function publish(Poll $poll): RedirectResponse
    {
        if ($poll->status === 'published') {
            return redirect()->route('admin.polls.edit', $poll)->with('error', 'Esta encuesta ya está publicada.');
        }

        if ($poll->questions()->count() === 0) {
            return redirect()->route('admin.polls.edit', $poll)->with('error', 'Debe agregar al menos una pregunta antes de publicar.');
        }

        $poll->publish();
        SendPollInvitations::dispatch($poll);

        return redirect()->route('admin.polls.index')->with('success', 'Encuesta publicada exitosamente. Los emails están siendo enviados.');
    }

    public function results(Poll $poll): View
    {
        if ($poll->status !== 'published') {
            abort(404);
        }

        $poll->load(['questions.options', 'questions.responses.user', 'tokens.user']);

        $totalMembers = User::where('active', true)->count();
        $totalResponses = $poll->getTotalResponsesCount();

        return view('admin.polls.results', compact('poll', 'totalMembers', 'totalResponses'));
    }
}
