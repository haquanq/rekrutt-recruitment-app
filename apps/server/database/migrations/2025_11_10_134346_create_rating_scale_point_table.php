<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("rating_scale_point", function (Blueprint $table) {
            $table->id();
            $table->string("label", 100);
            $table->string("definition", 300);
            $table->timestamps();

            $table
                ->foreignId("rating_scale_id")
                ->constrained(
                    table: "rating_scale",
                    indexName: "fk_rating_scale_point__rating_scale",
                );

            $table->primary(columns: ["id"], name: "pk_rating_scale_point");
            $table->unique(
                columns: ["rating_scale_id", "label"],
                name: "uq_rating_scale_point__label_per_scale",
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("rating_scale_point");
    }
};
