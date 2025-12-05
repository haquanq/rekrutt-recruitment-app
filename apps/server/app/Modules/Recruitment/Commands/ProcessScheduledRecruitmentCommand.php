<?php

namespace App\Modules\Recruitment\Commands;

use App\Modules\Recruitment\Enums\RecruitmentStatus;
use App\Modules\Recruitment\Models\Recruitment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ProcessScheduledRecruitmentCommand extends Command
{
    protected $signature = "recruitment:process-sheduled";
    protected $description = "Process scheduled recruitment statuses.";

    public function handle(): void
    {
        Recruitment::where("status", RecruitmentStatus::SCHEDULED->value)
            ->where("scheduled_start_at", "<=", Carbon::now())
            ->where("status", "=", RecruitmentStatus::SCHEDULED->value)
            ->update(["status" => RecruitmentStatus::PUBLISHED->value, "published_at" => Carbon::now()]);

        Recruitment::where("status", RecruitmentStatus::PUBLISHED->value)
            ->where("scheduled_end_at", "<=", Carbon::now())
            ->update(["status" => RecruitmentStatus::CLOSED->value, "closed_at" => Carbon::now()]);
    }
}
