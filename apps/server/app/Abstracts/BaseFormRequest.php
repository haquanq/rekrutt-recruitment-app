<?php

namespace App\Abstracts;

use App\Helpers\ArrayHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    "message" => "Validation failed",
                    "details" => $validator->errors(),
                ],
                422,
            ),
        );
    }
    protected function prepareForValidation(): void
    {
        $convertedData = ArrayHelper::convertKeysToSnakeCase($this->all());
        $this->replace($convertedData);
    }
}
