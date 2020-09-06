<?php

namespace Modules\Governorate\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Governorate\Entities\Governorate;

class GovernorateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Governorate::create([
            'name:en' => 'AlSharkia',
            'name:ar' => 'الشرقية',
        ]);

        // $this->call("OthersTableSeeder");
    }
}
