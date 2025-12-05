<?php

use App\Modules\Recruitment\Enums\RecruitmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("recruitment", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("title", 200);
            $table->string("description", 500);
            $table->string("position_title", 100);
            $table->timestampTZ("scheduled_start_at");
            $table->timestampTZ("scheduled_end_at");
            $table->timestampTZ("published_at")->nullable();
            $table->timestampTZ("closed_at")->nullable();
            $table->timestampTZ("completed_at")->nullable();
            $table->timestampsTZ();

            $table->enum("status", RecruitmentStatus::cases())->default(RecruitmentStatus::DRAFT);

            $table->foreignId("created_by_user_id")->constrained("user", "id", "fk_recruitment__creator");
            $table->foreignId("closed_by_user_id")->nullable()->constrained("user", "id", "fk_recruitment__closer");
            $table->foreignId("proposal_id")->constrained("proposal", "id", "fk_recruitment__proposal");
        });

        DB::statement("ALTER TABLE public.recruitment ADD CONSTRAINT pk_recruitment PRIMARY KEY (id)");
    }

    public function down(): void
    {
        Schema::dropIfExists("recruitment");
    }
};
