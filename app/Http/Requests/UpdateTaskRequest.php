<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'files' => ['array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,doc,docx,jpg,png,txt', 'max:20480'],     // 20 MB max
            'due_at' => ['nullable', 'date', 'after_or_equal:today'],       // Allows today's date
            'priority'=> [Rule::enum(TaskPriority::class)],
            'status' => [Rule::enum(TaskStatus::class)],
        ];
    }
}
