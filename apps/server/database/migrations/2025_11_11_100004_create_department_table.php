<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("department", function (Blueprint $table) {
            $table->id("id");
            $table->string("name", 100);
            $table->string("description", 300);
            $table->timestamps();

            $table->primary(columns: ["id"], name: "pk_department");
            $table->unique(columns: ["name"], name: "uq_department__name");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("department");
    }
};
