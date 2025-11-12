<?php

use App\Enums\RecruitmentApplicationStatus;
use App\Enums\RecruitmentApplicationPriority;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("recruitment_application", function (Blueprint $table) {
            $table->id();
            $table->timestampTZ("completed_at")->nullable();
            $table->timestamps();

            $table
                ->enum("status", RecruitmentApplicationStatus::cases())
                ->default(RecruitmentApplicationStatus::PENDING);
            $table
                ->enum("priority", RecruitmentApplicationPriority::cases())
                ->default(RecruitmentApplicationPriority::MEDIUM);

            $table
                ->foreignId("recruitment_id")
                ->constrained(
                    table: "recruitment",
                    indexName: "fk_recruitment_application__recruitment",
                );
            $table
                ->foreignId("candidate_id")
                ->constrained(
                    table: "candidate",
                    indexName: "fk_recruitment_application__candidate",
                );

            $table->primary(
                columns: ["id"],
                name: "pk_recruitment_application",
            );
            $table->unique(
                columns: ["recruitment_id", "candidate_id"],
                name: "uq_recruitment_application__per_recruitment",
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("recruitment_application");
    }
};
