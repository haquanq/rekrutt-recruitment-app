<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("interview_type", function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("description", 300);
            $table->timestamps();

            $table->primary(columns: ["id"], name: "pk_interview_type");
            $table->unique(columns: ["name"], name: "uq_interview_type__name");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("interview_type");
    }
};
