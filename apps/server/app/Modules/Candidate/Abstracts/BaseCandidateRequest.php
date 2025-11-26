<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Rules\PhoneNumberRule;
use Illuminate\Validation\Rule;

abstract class BaseCandidateRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = \intval($this->route("id"));

        return [
            "first_name" => ["required", "string", "max:100"],
            "last_name" => ["required", "string", "max:100"],
            "date_of_birth" => ["required", "date", "before:today"],
            "address" => ["required", "string", "max:500"],
            "email" => ["required", "email", Rule::unique("candidate", "email")->ignore($id)],
            "phone_number" => [
                "required",
                new PhoneNumberRule(),
                Rule::unique("candidate", "phone_number")->ignore($id),
            ],
            "hiring_source_id" => ["required", "integer:strict", "exists:hiring_source,id"],
        ];
    }
}
