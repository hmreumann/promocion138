<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->route('poll')->isDraft();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'questions' => ['nullable', 'array'],
            'questions.*.id' => ['nullable', 'integer', 'exists:poll_questions,id'],
            'questions.*.question' => ['required', 'string', 'max:500'],
            'questions.*.type' => ['required', 'in:yes_no,multiple_choice,text,number'],
            'questions.*.required' => ['boolean'],
            'questions.*.order' => ['required', 'integer', 'min:0'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*.id' => ['nullable', 'integer', 'exists:poll_question_options,id'],
            'questions.*.options.*.option_text' => ['required', 'string', 'max:255'],
            'questions.*.options.*.order' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede exceder 255 caracteres.',
            'description.max' => 'La descripción no puede exceder 1000 caracteres.',
            'questions.*.question.required' => 'El texto de la pregunta es obligatorio.',
            'questions.*.question.max' => 'La pregunta no puede exceder 500 caracteres.',
            'questions.*.type.required' => 'El tipo de pregunta es obligatorio.',
            'questions.*.type.in' => 'Tipo de pregunta inválido.',
            'questions.*.order.required' => 'El orden es obligatorio.',
            'questions.*.options.*.option_text.required' => 'El texto de la opción es obligatorio.',
            'questions.*.options.*.option_text.max' => 'La opción no puede exceder 255 caracteres.',
        ];
    }
}
