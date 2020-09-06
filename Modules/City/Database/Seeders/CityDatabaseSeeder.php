<?php

namespace Modules\City\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\City\Entities\City;
use Modules\Governorate\Entities\Governorate;

class CityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        City::create([
            'name:en'   => 'Zagazig',
            'name:ar'   => 'الزقازيق',
            'governorate_id'    => Governorate::first()->id,
        ]);

        // $this->call("OthersTableSeeder");
    }
}
