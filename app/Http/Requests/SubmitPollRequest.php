<?php

namespace App\Http\Requests;

use App\Models\PollToken;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitPollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $token = PollToken::where('token', $this->route('token'))->first();

        return $token && $token->isValid();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $token = PollToken::where('token', $this->route('token'))->firstOrFail();
        $poll = $token->poll->load('questions');

        $rules = [];

        foreach ($poll->questions as $question) {
            $field = "responses.{$question->id}";
            $base = $question->required ? ['required'] : ['nullable'];

            $rules[$field] = match ($question->type) {
                'yes_no' => [...$base, 'in:yes,no'],
                'multiple_choice' => [...$base, 'integer', 'exists:poll_question_options,id'],
                'text' => [...$base, 'string', 'max:1000'],
                'number' => [...$base, 'numeric'],
                default => $base,
            };
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'responses.*.required' => 'Esta pregunta es obligatoria.',
            'responses.*.in' => 'Valor inválido.',
            'responses.*.integer' => 'Debe seleccionar una opción.',
            'responses.*.exists' => 'Opción inválida.',
            'responses.*.string' => 'Debe ser texto.',
            'responses.*.max' => 'El texto no puede exceder 1000 caracteres.',
            'responses.*.numeric' => 'Debe ser un número.',
        ];
    }
}
