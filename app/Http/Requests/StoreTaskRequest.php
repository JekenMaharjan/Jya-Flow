<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,doc,docx,jpg,png,txt', 'max:20480'],     // 20 MB max
            'due_at' => ['nullable', 'date', 'after_or_equal:today'],       // Allows today's date
            'priority'=> ['required', Rule::enum(TaskPriority::class)],
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your task.',
            'title.string' => 'String only please!',
            'title.max' => 'The title cannot exceed 100 character.',
        ];
    }
}
