<?php

namespace App\Modules\Interview\Abstracts;

use App\Abstracts\BaseFormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

abstract class BaseInterviewParticipantRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            /**
             * Note (role of participant)
             * @example Focus on technical skills
             */
            "note" => ["nullable", "string", "max:300"],
            /**
             * Id of User
             * @example 1
             */
            "user_id" => ["required", "integer:strict"],
        ];
    }

    public function withValidator(Validator $validator)
    {
        Log::info("awdawdaw");
    }
}
