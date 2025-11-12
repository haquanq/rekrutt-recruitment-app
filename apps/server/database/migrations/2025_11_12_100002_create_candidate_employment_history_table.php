<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("candidate_employment_history", function (
            Blueprint $table,
        ) {
            $table->id("id");
            $table->date("from_date");
            $table->date("to_date");
            $table->string("employer_name", 100);
            $table->string("employer_description", 300)->nullable();
            $table->string("position_title", 100);
            $table->string("position_duty", 300);
            $table->string("comment", 300)->nullable();
            $table->timestamps();

            $table
                ->foreignId("candidate_id")
                ->constrained(
                    table: "candidate",
                    indexName: "fk_candidate_employment_history__candidate",
                );

            $table->primary(
                columns: ["id"],
                name: "pk_candidate_employment_history",
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("candidate_employment_history");
    }
};
