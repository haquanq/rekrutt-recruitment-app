<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("hiring_source", function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->text("description");
            $table->string("site_url")->nullable();
            $table->timestamps();

            $table->primary(columns: ["id"], name: "pk_hiring_source");
            $table->unique(columns: ["name"], name: "uq_hiring_source__name");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("hiring_sources");
    }
};
