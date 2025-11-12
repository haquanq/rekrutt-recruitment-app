<?php

use App\Enums\RecruitmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("recruitment", function (Blueprint $table) {
            $table->id();
            $table->string("title", 200);
            $table->string("description", 500);
            $table->string("position_title", 100);
            $table->timestampTZ("scheduled_start_at");
            $table->timestampTZ("scheduled_end_at");
            $table->timestampTZ("published_at")->nullable();
            $table->timestampTZ("closed_at")->nullable();
            $table->timestampTZ("completed_at")->nullable();
            $table->timestamps();

            $table
                ->enum("status", RecruitmentStatus::cases())
                ->default(RecruitmentStatus::DRAFT);

            $table
                ->foreignId("user_id")
                ->constrained(table: "user", indexName: "fk_recruitment__user");
            $table
                ->foreignId("proposal_id")
                ->constrained(
                    table: "proposal",
                    indexName: "fk_recruitment__proposal",
                );

            $table->primary(columns: ["id"], name: "pk_recruitment");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("recruitments");
    }
};
