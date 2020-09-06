<?php

namespace Modules\Offer\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Offer\Entities\Offer;

class OfferDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Offer::create([
            'name:ar'   => 'خصم على الشنطه',
            'name:en'   => 'Offer on bag',
            'description:ar'   => 'احصل على شنطه و الاخرى هديه',
            'description:en'   => 'Get a bag and the other gift',
            'image'     => 'bag.png',
            'price'     => '50',
            'order'     => 1,
            'shop_id'   => 1,
        ]);

        // $this->call("OthersTableSeeder");
    }
}
