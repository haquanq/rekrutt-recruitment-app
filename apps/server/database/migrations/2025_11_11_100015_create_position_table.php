<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("position", function (Blueprint $table) {
            $table->id();
            $table->string("title", 100);
            $table->string("description", 300);
            $table->timestamps();

            $table
                ->foreignId("department_id")
                ->constrained(
                    table: "department",
                    indexName: "fk_position__department",
                );

            $table->primary(["id"], "pk_position");
            $table->unique(["title"], "uq_position__title");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("position");
    }
};
