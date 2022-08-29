<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @property string $isbn
 * @property string $title
 * @property string $description
 * @property array $authors
 */
class PostBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'isbn'        => 'bail|required|numeric|digits:13|unique:books,isbn',
            'title'       => 'required|string',
            'description' => 'required|string',
            'authors'     => 'required|array',
            'authors.*'   => 'required|integer|exists:authors,id',
        ];
    }
}
