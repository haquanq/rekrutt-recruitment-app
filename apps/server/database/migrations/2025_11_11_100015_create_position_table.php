<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("position", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("title", 100);
            $table->string("description", 500)->nullable();
            $table->timestamps();

            $table
                ->foreignId("department_id")
                ->constrained(
                    table: "department",
                    indexName: "fk_position__department",
                );

            $table->unique(["title"], "uq_position__title");
        });

        DB::statement(
            "ALTER TABLE public.position ADD CONSTRAINT pk_position PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("position");
    }
};
