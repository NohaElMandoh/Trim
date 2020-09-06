<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Category::create([
            'name:en'   => 'تسالى',
            'name:ar'   => 'snacks',
            'is_shop'   => 0
        ]);
        
        Category::create([
            'name:en'   => 'Shop',
            'name:ar'   => 'محل',
            'is_shop'   => 1
        ]);
    }
}
