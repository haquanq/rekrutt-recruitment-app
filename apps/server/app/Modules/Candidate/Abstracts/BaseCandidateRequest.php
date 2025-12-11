<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Candidate\Models\Candidate;
use App\Rules\PhoneNumberRule;
use Illuminate\Validation\Rule;

abstract class BaseCandidateRequest extends BaseFormRequest
{
    protected ?Candidate $candidate = null;

    public function getQueriedCandidateOrFail(string $param = "id"): Candidate
    {
        if ($this->candidate === null) {
            $this->candidate = Candidate::findOrFail($this->route($param));
        }

        return $this->candidate;
    }

    public function rules(): array
    {
        return [
            /**
             * First name
             * @example Lamar
             */
            "first_name" => ["required", "string", "max:100"],
            /**
             * Last name
             * @example Alexander
             */
            "last_name" => ["required", "string", "max:100"],
            /**
             * Date of birth
             * @example 1999-01-01
             */
            "date_of_birth" => ["required", "date", "before:today"],
            /**
             * Address
             * @example 123 Main St
             */
            "address" => ["required", "string", "max:500"],
            /**
             * Email address
             * @example lamar.xander2@outlook.com
             */
            "email" => ["required", "email", Rule::unique("candidate")->ignore($this->route("id"))],
            /**
             * Phone number
             * @example 123-456-7890
             */
            "phone_number" => [
                "required",
                new PhoneNumberRule(),
                Rule::unique("candidate")->ignore($this->route("id")),
            ],
            /**
             * Id of HiringSource where candidate originated
             * @example 123-456-7890
             */
            "hiring_source_id" => ["required", "integer:strict", "exists:hiring_source,id"],
        ];
    }
}
