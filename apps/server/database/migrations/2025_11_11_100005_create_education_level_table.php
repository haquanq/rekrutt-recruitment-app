<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("education_level", function (Blueprint $table) {
            $table->id("id");
            $table->string("name")->unique("uq_education_level_name");
            $table->text("description");
            $table->timestamps();

            $table->primary(columns: ["id"], name: "pk_education_level");
            $table->unique(columns: ["name"], name: "uq_education_level__name");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("education_level");
    }
};
