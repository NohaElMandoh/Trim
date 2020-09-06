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
            'address:en'    => 'Tolba ouda street, Zagazig, Egypt',
            'address:ar'    => 'شارع طلبه عويضه ,الزقازيق, مصر',
            'order'         => 1,
            'lat'           => 30.58768,
            'lng'           => 31.502
        ]);

        // $this->call("OthersTableSeeder");
    }
}
