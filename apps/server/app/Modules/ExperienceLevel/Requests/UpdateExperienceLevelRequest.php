<?php

namespace App\Modules\ExperienceLevel\Requests;

use App\Modules\ExperienceLevel\Abstracts\BaseExperienceLevelRequest;

class UpdateExperienceLevelRequest extends BaseExperienceLevelRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
