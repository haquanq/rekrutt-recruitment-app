<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("experience_level", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 500);
            $table->timestamps();

            $table->unique(
                columns: ["name"],
                name: "uq_experience_level__name",
            );
        });

        DB::statement(
            "ALTER TABLE public.experience_level ADD CONSTRAINT pk_experience_level PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("experience_levels");
    }
};
