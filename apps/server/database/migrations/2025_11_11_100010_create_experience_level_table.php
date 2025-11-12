<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("experience_level", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->timestamps();

            $table->primary(columns: ["id"], name: "pk_experience_level");
            $table->unique(
                columns: ["name"],
                name: "uq_experience_level__name",
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("experience_levels");
    }
};
