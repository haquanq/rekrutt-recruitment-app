<?php

namespace App\Modules\Candidate\Resources;

use App\Abstracts\BaseResourceCollection;

class CandidateResourceCollection extends BaseResourceCollection
{
    public $collects = CandidateResource::class;
}
