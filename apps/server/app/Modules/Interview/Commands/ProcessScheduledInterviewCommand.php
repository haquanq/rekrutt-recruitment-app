<?php

namespace App\Modules\Interview\Commands;

use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessScheduledInterviewCommand extends Command
{
    protected $signature = "interview:process-scheduled";
    protected $description = "Process scheduled interview statuses.";

    public function handle(): void
    {
        $currentTimeText = Carbon::now()->toDateTimeString();
        $underEvaluationStatus = InterviewStatus::UNDER_EVALUATION->value;
        $inProgressStatus = InterviewStatus::IN_PROGRESS->value;

        Interview::query()->update([
            "status" => DB::raw(
                "CASE WHEN started_at <= '$currentTimeText' THEN '$inProgressStatus' " .
                    " WHEN ended_at <= '$currentTimeText' THEN '$underEvaluationStatus' END",
            ),
        ]);
    }
}
