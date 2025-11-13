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
        Schema::create("contract_type", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("name", 100);
            $table->string("description", 300);
            $table->timestamps();

            $table->unique(columns: ["name"], name: "uq_contract_type__name");
        });

        DB::statement(
            "ALTER TABLE public.contract_type ADD CONSTRAINT pk_contract_type PRIMARY KEY (id)",
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("contract_type");
    }
};
