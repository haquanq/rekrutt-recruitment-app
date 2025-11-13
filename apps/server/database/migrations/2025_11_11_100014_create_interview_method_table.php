<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("interview_method", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 500)->nullable();
            $table->timestamps();

            $table->unique(
                columns: ["name"],
                name: "uq_interview_method__name",
            );
        });

        DB::statement(
            "ALTER TABLE public.interview_method ADD CONSTRAINT pk_interview_method PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("interview_method");
    }
};
