<?php

use App\Enums\InterviewStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("interview", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->timestampTZ("scheduled_start_at");
            $table->timestampTZ("schedules_end_at");
            $table->timestampTZ("started_at")->nullable();
            $table->timestampTZ("ended_at")->nullable();
            $table->timestampTZ("cancelled_at")->nullable();
            $table->text("cancellation_note")->nullable();
            $table
                ->enum("status", InterviewStatus::cases())
                ->default(InterviewStatus::DRAFT);
            $table->timestamps();

            $table
                ->foreignId("recruitment_application_id")
                ->constrained(
                    table: "recruitment_application",
                    indexName: "fk_interview__recruitment_application",
                );
            $table
                ->foreignId("interview_type_id")
                ->constrained(
                    table: "interview_type",
                    indexName: "fk_interview__interview_type",
                );
            $table
                ->foreignId("user_id")
                ->constrained(table: "user", indexName: "fk_interview__user");

            $table->primary(columns: ["id"], name: "pk_interview");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("interview");
    }
};
