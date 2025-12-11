<?php

namespace App\Modules\Recruitment\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Recruitment\Models\RecruitmentApplication;

abstract class BaseRecruitmentApplicationRequest extends BaseFormRequest
{
    protected ?RecruitmentApplication $recruitmentApplication = null;

    public function getRecruitmentApplicationOrFail(string $param = "id"): RecruitmentApplication
    {
        if ($this->recruitmentApplication === null) {
            $this->recruitmentApplication = RecruitmentApplication::findOrFail($this->route($param));
        }

        return $this->recruitmentApplication;
    }
}
