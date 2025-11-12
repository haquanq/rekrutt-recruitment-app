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
            $table->id();
            $table->string("name", 100);
            $table->string("description", 300)->nullable();
            $table->boolean("is_active")->default(0);
            $table->timestamps();

            $table->primary(columns: ["id"], name: "pk_rating_scale");
            $table->unique(columns: ["name"], name: "uq_rating_scale__name");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("rating_scale");
    }
};
