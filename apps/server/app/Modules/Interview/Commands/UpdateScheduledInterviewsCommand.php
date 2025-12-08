<?php

namespace App\Modules\Interview\Commands;

use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateScheduledInterviewsCommand extends Command
{
    protected $signature = "interview:update-scheduled";
    protected $description = "Update scheduled interviews.";

    public function handle(): void
    {
        $currentTimeText = Carbon::now()->toDateTimeString();
        $scheduledStatus = InterviewStatus::SCHEDULED->value;
        $underEvaluationStatus = InterviewStatus::UNDER_EVALUATION->value;
        $inProgressStatus = InterviewStatus::IN_PROGRESS->value;

        Interview::query()->update([
            "status" => DB::raw(
                "CASE WHEN started_at <= '$currentTimeText' and status = '$scheduledStatus' THEN '$inProgressStatus' " .
                    " WHEN ended_at <= '$currentTimeText' and status = '$inProgressStatus' THEN '$underEvaluationStatus' ELSE status END",
            ),
        ]);
    }
}
