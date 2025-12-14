<?php

namespace App\Modules\Recruitment\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCompletedRecruitmentsCommand extends Command
{
    protected $signature = "recruitment:update-completed";
    protected $description = "Update completed recruitments.";

    public function handle(): void
    {
        DB::unprepared("
            DO
            $$
            BEGIN

                CREATE TEMP TABLE proposal_new_hire ON COMMIT DROP AS
                SELECT a.id as recruitment_id, a.proposal_id, COUNT(CASE WHEN b.status = 'OFFER_ACCEPTED' THEN 1 ELSE NULL END) AS numbers_of_new_hires
                FROM recruitment a JOIN recruitment_application b ON a.id = b.recruitment_id
                WHERE a.status = 'CLOSED'
                GROUP BY a.id
                HAVING COUNT(CASE WHEN b.status IN ('WITHDRAWN', 'DISCARDED', 'OFFER_ACCEPTED', 'OFFER_REJECTED') THEN 1 ELSE NULL END) = COUNT(b.id);

                UPDATE proposal
                SET total_hires = total_hires + b.numbers_of_new_hires
                FROM proposal_new_hire b
                WHERE id = b.proposal_id;

                UPDATE recruitment
                SET status = 'COMPLETED'
                FROM proposal_new_hire b
                WHERE id = b.recruitment_id;

            END;
            $$;

        ");
    }
}
