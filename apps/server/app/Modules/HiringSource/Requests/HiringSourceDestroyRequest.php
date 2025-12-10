<?php

namespace App\Modules\HiringSource\Requests;

use App\Modules\HiringSource\Abstracts\BaseHiringSourceRequest;
use App\Modules\HiringSource\Models\HiringSource;
use Illuminate\Support\Facades\Gate;

class HiringSourceDestroyRequest extends BaseHiringSourceRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", HiringSource::class);
        return true;
    }
}
