<?php

namespace App\Modules\HiringSource\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\HiringSource\Models\HiringSource;

abstract class BaseHiringSourceRequest extends BaseFormRequest
{
    protected ?HiringSource $hiringSource = null;

    public function rules(): array
    {
        return [
            /**
             * Name
             * @example LinkedIn
             */
            "name" => ["required", "string", "max:100"],
            /**
             * Description
             * @example LinkedIn is a professional networking and career advancement platform
             */
            "description" => ["nullable", "string", "max:500"],
            /**
             * Site URL
             * @example https://www.linkedin.com/
             */
            "site_url" => ["nullable", "string"],
        ];
    }

    public function getHiringSourceOrFail(string $param = null): HiringSource
    {
        if ($this->hiringSource === null) {
            $this->hiringSource = HiringSource::findOrFail($this->route($param ?? "id"));
        }

        return $this->hiringSource;
    }
}
