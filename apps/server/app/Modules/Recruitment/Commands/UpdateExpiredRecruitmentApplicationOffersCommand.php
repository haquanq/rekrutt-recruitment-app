<?php

namespace App\Modules\Recruitment\Commands;

use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateExpiredRecruitmentApplicationOffersCommand extends Command
{
    protected $signature = "recruitment-application:update-expired-offers";
    protected $description = "Update expired recruitment application offers to rejected.";

    public function handle(): void
    {
        RecruitmentApplication::query()
            ->where("status", RecruitmentApplicationStatus::OFFER_PENDING->value)
            ->where("offer_expired_at", "<=", Carbon::now())
            ->update([
                "status" => RecruitmentApplicationStatus::OFFER_REJECTED->value,
                "offer_responded_at" => Carbon::now(),
                "offer_rejected_reason" => "Offer expired (reject by system)",
            ]);
    }
}
