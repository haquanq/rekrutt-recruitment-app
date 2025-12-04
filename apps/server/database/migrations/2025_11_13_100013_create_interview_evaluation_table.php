<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("interview_evaluation", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("comment", 500);
            $table->timestampsTZ();

            $table
                ->foreignId("interview_id")
                ->constrained(table: "interview", indexName: "fk_interview_evaluation__interview")
                ->onDelete("cascade");

            $table
                ->foreignId("created_by_user_id")
                ->constrained(table: "user", indexName: "fk_interview_evaluation__created_by")
                ->onDelete("cascade");

            $table
                ->foreignId("rating_scale_point_id")
                ->constrained(table: "rating_scale_point", indexName: "fk_interview_evaluation__rating_scale_point");

            $table->unique(
                columns: ["interview_id", "user_id"],
                name: "uq_interview_evaluation__per_interview_by_user",
            );
        });

        DB::statement(
            "ALTER TABLE public.interview_evaluation ADD CONSTRAINT pk_interview_evaluation PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("interview_evaluation");
    }
};
