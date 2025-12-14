<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("proposal_document", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->text("file_path");
            $table->string("file_name", 256);
            $table->string("file_extension", 50)->nullable();
            $table->string("mime_type", 300);
            $table->string("notes", 500)->nullable();
            $table->timestampsTZ();

            $table
                ->foreignId("proposal_id")
                ->constrained(table: "proposal", indexName: "fk_proposal_document__proposal")
                ->onDelete("cascade");
        });

        DB::statement("ALTER TABLE public.proposal_document ADD CONSTRAINT pk_proposal_document PRIMARY KEY (id)");
    }

    public function down(): void
    {
        Schema::dropIfExists("proposal_document");
    }
};
