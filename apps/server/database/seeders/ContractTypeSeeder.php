<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContractTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(
            File::get(database_path("/data/contract-type.json")),
            true,
        );

        SeederHelper::addTimeStamps($items);
        DB::table("contract_type")->insert($items);
    }
}
