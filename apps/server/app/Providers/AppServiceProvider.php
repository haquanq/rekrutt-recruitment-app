<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Route::pattern("id", "[0-9]+");

        Builder::macro("autoPaginate", function (
            $defaultPerPage = 15,
            $columns = ["*"],
            $pageName = "page",
            $page = null,
        ) {
            $pageSize = (int) request()->input("page.size", $defaultPerPage);
            $pageNumber = (int) request()->input("page.number", $page);

            $pageSize = min($pageSize, 100);
            $pageSize = max($pageSize, 1);

            return $this->paginate($pageSize, $columns, $pageName, $pageNumber);
        });
    }

    public function boot(): void
    {
        if ($this->app->environment("local")) {
            DB::listen(function ($query) {
                Log::info(
                    "SQL ___ " . $query->sql . " ___ " . json_encode($query->bindings) . " ___ " . $query->time . "ms",
                );
            });
        }
    }
}
