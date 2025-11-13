<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("education_level", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 300);
            $table->timestamps();

            $table->unique(columns: ["name"], name: "uq_education_level__name");
        });

        DB::statement(
            "ALTER TABLE public.education_level ADD CONSTRAINT pk_education_level PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("education_level");
    }
};
