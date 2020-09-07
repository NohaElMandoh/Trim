<?php

namespace Modules\Address\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Address\Entities\Address;

class AddressDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Address::create([
            'address:en'    => 'Cairo, Egypt',
            'address:ar'    => 'القاهرة, مصر',
            'order'         => 1,
            'lat'           => 30.033333,
            'lng'           => 31.233334
        ]);

        // $this->call("OthersTableSeeder");
    }
}
