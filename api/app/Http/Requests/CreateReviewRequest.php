<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateReviewRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reviews' => 'required|array',
            'reviews.*.article_id' => [
                'required',
                'exists:articles,id',
                Rule::unique('reviews', 'article_id')->where('user_id', auth()->id())
            ],
            'reviews.*.status' => 'required|integer|max:1',
            'reviews.*.comment' => 'nullable|string',
        ];
    }
}
