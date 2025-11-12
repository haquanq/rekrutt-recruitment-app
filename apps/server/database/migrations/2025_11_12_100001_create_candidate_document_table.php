<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("candidate_document", function (Blueprint $table) {
            $table->uuid("id");
            $table->string("file_id", 300);
            $table->string("file_name", 100);
            $table->string("file_url")->nullable();
            $table->string("mime_type", 300);
            $table->string("comment", 300)->nullable();
            $table->timestamps();

            $table
                ->foreignId("candidate_id")
                ->constrained(
                    table: "candidate",
                    indexName: "fk_candidate_document__candidate",
                )
                ->onDelete("cascade");

            $table->primary(columns: ["id"], name: "pk_candidate_document");
            $table->unique(
                columns: ["file_id", "candidate_id"],
                name: "uq_candidate_document__file",
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("candidate_document");
    }
};
