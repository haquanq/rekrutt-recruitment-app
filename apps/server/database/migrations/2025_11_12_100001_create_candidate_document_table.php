<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("candidate_document", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("file_id", 256);
            $table->string("file_name", 256);
            $table->string("file_url", 500)->nullable();
            $table->string("file_extension", 50)->nullable();
            $table->string("mime_type", 300);
            $table->string("notes", 500)->nullable();
            $table->timestampsTZ();

            $table
                ->foreignId("candidate_id")
                ->constrained(table: "candidate", indexName: "fk_candidate_document__candidate")
                ->onDelete("cascade");
        });

        DB::statement("ALTER TABLE public.candidate_document ADD CONSTRAINT pk_candidate_document PRIMARY KEY (id)");
    }

    public function down(): void
    {
        Schema::dropIfExists("candidate_document");
    }
};
