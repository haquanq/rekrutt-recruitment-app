<?php

namespace App\Modules\HiringSource\Requests;

use App\Modules\HiringSource\Abstracts\BaseHiringSourceRequest;
use App\Modules\HiringSource\Models\HiringSource;
use Illuminate\Support\Facades\Gate;

class HiringSourceStoreRequest extends BaseHiringSourceRequest
{
    public function authorize(): bool
    {
        Gate::authorize("create", HiringSource::class);
        return true;
    }
}
