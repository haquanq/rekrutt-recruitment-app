<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("department", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 500)->nullable();
            $table->timestamps();

            $table->unique(columns: ["name"], name: "uq_department__name");
        });

        DB::statement(
            "ALTER TABLE public.department ADD CONSTRAINT pk_department PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("department");
    }
};
