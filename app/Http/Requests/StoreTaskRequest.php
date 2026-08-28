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
            'description' => ['string', 'max:500'],
            'file_path' => ['file', 'mimes:pdf,doc,docx,jpg,png', 'max:20480'],
            'due_at' => ['date', 'after:today'],
            'priority'=> [Rule::enum(TaskPriority::class)],
            'status' => [Rule::enum(TaskStatus::class)],
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
