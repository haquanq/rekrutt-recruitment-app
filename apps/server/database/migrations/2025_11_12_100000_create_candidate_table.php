<?php

use App\Enums\CandidateStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("candidate", function (Blueprint $table) {
            $table->id("id");
            $table->string("first_name", 100);
            $table->string("last_name", 100);
            $table->date("date_of_birth");
            $table->string("address", 256);
            $table->string("email", 256);
            $table->string("phone", 15);
            $table
                ->enum("status", CandidateStatus::cases())
                ->default(CandidateStatus::NEW);
            $table->timestamps();

            $table
                ->foreignId("hiring_source_id")
                ->constrained(
                    table: "hiring_source",
                    indexName: "fk_candidate__hiring_source",
                );

            $table->primary(columns: ["id"], name: "pk_candidate");
            $table->unique(columns: ["email"], name: "uq_candidate__email");
            $table->unique(columns: ["phone"], name: "uq_candidate__phone");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("candidate");
    }
};
