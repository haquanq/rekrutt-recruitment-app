<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewParticipantRequest;
use App\Modules\Interview\Models\InterviewParticipant;
use Illuminate\Support\Facades\Gate;

class InterviewParticipantUpdateRequest extends BaseInterviewParticipantRequest
{
    public InterviewParticipant $interviewParticipant;

    public function authorize(): bool
    {
        Gate::authorize("update", $this->interviewParticipant);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interview = InterviewParticipant::with("interview")->findOrFail($this->route("id"));
    }
}
