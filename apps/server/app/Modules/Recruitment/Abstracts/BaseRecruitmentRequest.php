<?php

namespace App\Modules\Recruitment\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Recruitment\Models\Recruitment;

abstract class BaseRecruitmentRequest extends BaseFormRequest
{
    protected ?Recruitment $recruitment = null;

    public function getQueriedRecruitmentOrFail(string $param = "id"): Recruitment
    {
        if ($this->recruitment === null) {
            $this->recruitment = Recruitment::findOrFail($this->route($param));
        }

        return $this->recruitment;
    }

    public function rules(): array
    {
        return [
            /**
             * Title
             * @example Tech Talent Wanted: Join Rekrutt as a Software Engineer and Shape the Future of Technology
             */
            "title" => ["required", "string", "max:100"],
            /**
             * Description
             * @example Rekrutt seeks talented Software Engineers to join our dynamic team and collaborate on innovative projects, leveraging cutting-edge technologies to deliver impactful solutions in various industries
             */
            "description" => ["string", "max:500"],
            /**
             * Position title
             * @example Senior Software Engineer
             */
            "position_title" => ["required", "string", "max:100"],
        ];
    }
}
