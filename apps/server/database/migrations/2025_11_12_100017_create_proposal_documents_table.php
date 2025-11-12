<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("proposal_document", function (Blueprint $table) {
            $table->id();
            $table->string("file_id", 300);
            $table->string("file_name", 100);
            $table->text("file_url")->nullable();
            $table->string("mime_type", 300);
            $table->string("comment", 300)->nullable();
            $table->timestamps();

            $table
                ->foreignId("proposal_id")
                ->constrained(
                    table: "proposal",
                    indexName: "fk_proposal_document__proposal",
                )
                ->onDelete("cascade");

            $table->primary(columns: ["id"], name: "pk_proposal_document");
            $table->unique(
                columns: ["file_id", "proposal_id"],
                name: "uq_proposal_document__file_id_proposal_id",
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("proposal_document");
    }
};
