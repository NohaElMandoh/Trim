<?php

namespace Modules\Package\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Package\Entities\Package;

class PackageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Package::create([
            'description:en'    => 'Get points now',
            'description:ar'    => 'أحصل على النقاط الأن',
            'points'            => 50,
            'price'             => 50,
            'order'             => 1
        ]);

        // $this->call("OthersTableSeeder");
    }
}
