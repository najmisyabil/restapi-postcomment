<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchByKeywords extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keywords' => 'required|array',
            'keywords.*' => 'required|string',
        ];
    }

    public function failedValidation(Validator $v)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $v->errors()->first(),
            ])
        );
    }
}
