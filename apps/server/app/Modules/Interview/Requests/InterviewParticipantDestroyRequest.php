<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewParticipantRequest;
use App\Modules\Interview\Models\InterviewParticipant;
use Illuminate\Support\Facades\Gate;

class InterviewParticipantDestroyRequest extends BaseInterviewParticipantRequest
{
    public InterviewParticipant $interviewParticipant;

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->interviewParticipant);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interviewParticipant = InterviewParticipant::with("interview")->findOrFail($this->route("id"));
    }
}
