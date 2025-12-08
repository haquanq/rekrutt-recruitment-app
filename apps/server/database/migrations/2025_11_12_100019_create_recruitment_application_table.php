<?php

use App\Modules\Recruitment\Enums\RecruitmentApplicationPriority;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("recruitment_application", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->integer("number_of_interviews")->nullable();

            $table->timestampTZ("withdrawn_at")->nullable();
            $table->string("withdrawn_reason")->nullable();

            $table->timestampTZ("discarded_at")->nullable();
            $table->string("discard_reason", 300)->nullable();
            $table
                ->foreignId("discarded_by_user_id")
                ->nullable()
                ->constrained(table: "user", indexName: "fk_recruitment_application__discarded_by_user");

            $table->timestampTZ("offer_started_at")->nullable();
            $table->timestampTZ("offer_expired_at")->nullable();
            $table->timestampTZ("offer_responded_at")->nullable();
            $table->string("offer_rejected_reason", 300)->nullable();

            $table->timestampsTZ();

            $table
                ->enum("status", RecruitmentApplicationStatus::cases())
                ->default(RecruitmentApplicationStatus::PENDING);
            $table
                ->enum("priority", RecruitmentApplicationPriority::cases())
                ->default(RecruitmentApplicationPriority::MEDIUM);

            $table
                ->foreignId("recruitment_id")
                ->constrained(table: "recruitment", indexName: "fk_recruitment_application__recruitment");
            $table
                ->foreignId("candidate_id")
                ->constrained(table: "candidate", indexName: "fk_recruitment_application__candidate");

            $table->unique(
                columns: ["recruitment_id", "candidate_id"],
                name: "uq_recruitment_application__per_recruitment",
            );
        });

        DB::statement(
            "ALTER TABLE public.recruitment_application ADD CONSTRAINT pk_recruitment_application PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("recruitment_application");
    }
};
