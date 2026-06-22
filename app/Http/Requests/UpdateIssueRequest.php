<?php

namespace App\Http\Requests;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIssueRequest extends FormRequest
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
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'status' => ['required', Rule::enum(IssueStatus::class)],
            'priority' => ['required', Rule::enum(IssuePriority::class)],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
