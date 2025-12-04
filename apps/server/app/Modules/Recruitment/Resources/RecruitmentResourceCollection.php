<?php

namespace App\Modules\Recruitment\Resources;

use App\Abstracts\BaseResourceCollection;

class RecruitmentResourceCollection extends BaseResourceCollection
{
    public $collects = RecruitmentResource::class;
}
