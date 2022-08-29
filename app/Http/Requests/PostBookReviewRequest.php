<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property integer $review
 * @property string $comment
 */
class PostBookReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'review'  => 'required|integer|between:1,10',
            'comment' => 'required|string'
        ];
    }
}
