<?php

namespace Modules\Feature\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Feature\Entities\Feature;

class FeatureDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Feature::create([
            'title:en'  => 'High quality',
            'title:ar'  => 'جودة عالية',
            'image'     => 'feature.png',
            'order'     => 1
        ]);

        // $this->call("OthersTableSeeder");
    }
}
