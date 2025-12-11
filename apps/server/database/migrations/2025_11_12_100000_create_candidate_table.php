<?php

use App\Modules\Candidate\Enums\CandidateStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("candidate", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("first_name", 100);
            $table->string("last_name", 100);
            $table->date("date_of_birth");
            $table->string("address", 256);
            $table->string("email", 256);
            $table->string("phone_number", 15);
            $table->enum("status", CandidateStatus::cases())->default(CandidateStatus::READY->value);
            $table->timestampsTZ();

            $table
                ->foreignId("hiring_source_id")
                ->constrained(table: "hiring_source", indexName: "fk_candidate__hiring_source");

            $table->unique(columns: ["email"], name: "uq_candidate__email");
            $table->unique(columns: ["phone_number"], name: "uq_candidate__phone_number");
        });

        DB::statement("ALTER TABLE public.candidate ADD CONSTRAINT pk_candidate PRIMARY KEY (id)");
    }

    public function down(): void
    {
        Schema::dropIfExists("candidate");
    }
};
