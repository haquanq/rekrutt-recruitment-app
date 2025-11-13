<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("rating_scale", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 500)->nullable();
            $table->boolean("is_active")->default(0);
            $table->timestamps();

            $table->unique(columns: ["name"], name: "uq_rating_scale__name");
        });

        DB::statement(
            "ALTER TABLE public.rating_scale ADD CONSTRAINT pk_rating_scale PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("rating_scale");
    }
};
