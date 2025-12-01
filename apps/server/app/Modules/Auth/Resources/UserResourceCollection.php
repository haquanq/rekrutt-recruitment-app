<?php

namespace App\Modules\Auth\Resources;

use App\Abstracts\BaseResourceCollection;

class UserResourceCollection extends BaseResourceCollection
{
    public $collects = UserResource::class;
}
