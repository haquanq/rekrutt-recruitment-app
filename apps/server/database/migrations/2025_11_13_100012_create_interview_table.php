<?php

use App\Modules\Interview\Enums\InterviewStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("interview", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("title", 100);
            $table->string("description", 500);
            $table->integer("round");
            $table->string("location", 300);
            $table->timestampTZ("started_at")->nullable();
            $table->timestampTZ("ended_at")->nullable();
            $table->timestampTZ("cancelled_at")->nullable();
            $table->string("cancellation_reason", 300)->nullable();
            $table->enum("status", InterviewStatus::cases())->default(InterviewStatus::DRAFT);
            $table->timestampsTZ();

            $table
                ->foreignId("rating_scale_id")
                ->constrained(table: "rating_scale", indexName: "fk_interview__rating_scale");

            $table
                ->foreignId("recruitment_application_id")
                ->constrained(table: "recruitment_application", indexName: "fk_interview__recruitment_application");

            $table
                ->foreignId("interview_method_id")
                ->constrained(table: "interview_method", indexName: "fk_interview__interview_method");

            $table->foreignId("created_by_user_id")->constrained(table: "user", indexName: "fk_interview__creator");

            $table->foreignId("cancelled_by_user_id")->constrained(table: "user", indexName: "fk_interview__canceller");
        });

        DB::statement("ALTER TABLE public.interview ADD CONSTRAINT pk_interview PRIMARY KEY (id)");
    }

    public function down(): void
    {
        Schema::dropIfExists("interview");
    }
};
