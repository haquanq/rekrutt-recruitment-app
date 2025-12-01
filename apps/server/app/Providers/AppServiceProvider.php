<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
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

    public function boot(): void {}
}
