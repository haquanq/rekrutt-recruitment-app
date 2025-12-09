<?php

namespace App\Modules\Recruitment\Commands;

use App\Modules\Recruitment\Enums\RecruitmentStatus;
use App\Modules\Recruitment\Models\Recruitment;
use Illuminate\Console\Command;

class UpdateCompletedRecruitmentsCommand extends Command
{
    protected $signature = "recruitment:update-completed";
    protected $description = "Update completed recruitments.";

    public function handle(): void
    {
        $closedRecruitments = Recruitment::withCount([
            "applications",
            "applications as completed_applications" => function ($query) {
                $query->completed();
            },
        ])
            ->where(["status" => RecruitmentStatus::CLOSED->value])
            ->get();

        $completedRecruitmentIds = $closedRecruitments
            ->filter(function ($recruitment) {
                return $recruitment->applications_count === $recruitment->completed_applications_count;
            })
            ->pluck("id")
            ->toArray();

        Recruitment::query()
            ->whereIn("id", $completedRecruitmentIds)
            ->update([
                "status" => RecruitmentStatus::COMPLETED,
            ]);
    }
}
