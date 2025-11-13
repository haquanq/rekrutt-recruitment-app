<?php

use App\Modules\Interview\Enums\InterviewStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("interview", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("title", 100);
            $table->string("description", 300);
            $table->timestampTZ("scheduled_start_at");
            $table->timestampTZ("schedules_end_at");
            $table->timestampTZ("started_at")->nullable();
            $table->timestampTZ("ended_at")->nullable();
            $table->timestampTZ("cancelled_at")->nullable();
            $table->string("cancellation_note", 300)->nullable();
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
                ->foreignId("interview_method_id")
                ->constrained(
                    table: "interview_method",
                    indexName: "fk_interview__interview_method",
                );
            $table
                ->foreignId("user_id")
                ->constrained(table: "user", indexName: "fk_interview__user");
        });

        DB::statement(
            "ALTER TABLE public.interview ADD CONSTRAINT pk_interview PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("interview");
    }
};
