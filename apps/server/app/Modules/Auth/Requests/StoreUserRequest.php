<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;

class StoreUserRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
