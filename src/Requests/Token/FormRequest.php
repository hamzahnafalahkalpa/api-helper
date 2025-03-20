<?php

namespace Hanafalah\ApiHelper\Requests\Token;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {


        return 1 == 1;
    }

    public function rules(): array
    {
        return [
            'token' => ['nullable', 'string', 'max:255']
        ];
    }
}
