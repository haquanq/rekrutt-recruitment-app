<?php

namespace App\Modules\Position\Resources;

use App\Abstracts\BaseResourceCollection;

class PositionResourceCollection extends BaseResourceCollection
{
    public $collects = PositionResource::class;
}
