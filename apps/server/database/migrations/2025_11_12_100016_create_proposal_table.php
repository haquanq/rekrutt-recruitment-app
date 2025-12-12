<?php

use App\Modules\Proposal\Enums\ProposalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("proposal", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("title", 200);
            $table->string("description", 500);
            $table->integer("target_hires");
            $table->integer("total_hires")->default(0);
            $table->integer("min_salary");
            $table->integer("max_salary");
            $table->enum("status", ProposalStatus::cases())->default(ProposalStatus::DRAFT);

            $table->string("reviewed_notes", 500)->nullable();
            $table->timestampTz("reviewed_at")->nullable();
            $table
                ->foreignId("reviewed_by_user_id")
                ->nullable()
                ->constrained(table: "user", indexName: "fk_proposal__reviewer");

            $table
                ->foreignId("contract_type_id")
                ->constrained(table: "contract_type", indexName: "fk_proposal__contract_type");
            $table
                ->foreignId("education_level_id")
                ->constrained(table: "education_level", indexName: "fk_proposal__education_level");
            $table
                ->foreignId("experience_level_id")
                ->constrained(table: "experience_level", indexName: "fk_proposal__experience_level");
            $table->foreignId("position_id")->constrained(table: "position", indexName: "fk_proposal__position");

            $table->timestampsTZ();
            $table->foreignId("created_by_user_id")->constrained(table: "user", indexName: "fk_proposal__creator");

            $table->unique(columns: ["title"], name: "uq_proposal__title");
        });

        DB::statement("ALTER TABLE public.proposal ADD CONSTRAINT pk_proposal PRIMARY KEY (id)");
    }

    public function down(): void
    {
        Schema::dropIfExists("proposal");
    }
};
