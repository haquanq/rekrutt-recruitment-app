<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RatingScaleSeeder extends Seeder
{
    public function run(): void
    {
        $ratingScales = json_decode(
            File::get(database_path("/data/rating-scale.json")),
            true,
        );

        SeederHelper::addTimeStamps($ratingScales);
        foreach ($ratingScales as $ratingScale) {
            $ratingScalePoints = $ratingScale["points"];
            unset($ratingScale["points"]);
            $ratingScaleId = DB::table("rating_scale")->insertGetId(
                $ratingScale,
            );

            foreach ($ratingScalePoints as &$ratingScalePoint) {
                $ratingScalePoint["rating_scale_id"] = $ratingScaleId;
            }
            SeederHelper::addTimeStamps($ratingScalePoints);
            DB::table("rating_scale_point")->insert($ratingScalePoints);
        }
    }
}
