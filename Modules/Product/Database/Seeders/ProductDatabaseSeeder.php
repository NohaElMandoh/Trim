<?php

namespace Modules\Product\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Product::create([
            'name:ar'   => 'شنطة جلد',
            'name:en'   => 'Leather bag',
            'image'     => 'bag.png',
            'price'     => '60',
            'order'     => 1,
            'shop_id'   => 1,
        ]);

        // $this->call("OthersTableSeeder");
    }
}
