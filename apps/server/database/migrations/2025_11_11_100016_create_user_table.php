<?php

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("user", function (Blueprint $table) {
            $table->bigInteger("id")->generatedAs()->always();
            $table->string("first_name", 100);
            $table->string("last_name", 100);
            $table->string("email", 300);
            $table->string("phone", 15);
            $table->string("username", 40);
            $table->string("password", 30);
            $table->timestampTz("suspended_at")->nullable();
            $table->string("suspension_note", 500);
            $table->timestampTz("retired_at")->nullable();
            $table->timestamps();

            $table->enum("role", UserRole::cases());

            $table
                ->enum("status", UserStatus::cases())
                ->default(UserStatus::ACTIVE);

            $table
                ->foreignId("position_id")
                ->constrained(
                    table: "position",
                    indexName: "fk_user__position",
                );

            $table->unique(columns: ["email"], name: "uq_user__email");
            $table->unique(columns: ["phone"], name: "uq_user__phone");
            $table->unique(columns: ["username"], name: "uq_user__username");
        });

        DB::statement(
            "ALTER TABLE public.user ADD CONSTRAINT pk_user PRIMARY KEY (id)",
        );
    }

    public function down(): void
    {
        Schema::dropIfExists("user");
    }
};
