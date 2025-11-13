<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("hiring_source", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 500);
            $table->string("site_url")->nullable();
            $table->timestamps();

            $table->unique(columns: ["name"], name: "uq_hiring_source__name");
        });

        DB::statement(
            "ALTER TABLE public.hiring_source ADD CONSTRAINT pk_hiring_source PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("hiring_sources");
    }
};
