<?php

namespace Modules\Status\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Status\Entities\Status;

class StatusDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Status::create([
            'name:en'   => 'Searching for a captain',
            'name:ar'   => 'جارى البحث عن كابتن',
            'slug'      => 'search-captain'
        ]);

        Status::create([
            'name:en'   => 'Cancelled',
            'name:ar'   => 'ملغى',
            'slug'      => 'cancelled'
        ]);

        Status::create([
            'name:en'   => 'Processing',
            'name:ar'   => 'جارى التنفيذ',
            'slug'      => 'processing'
        ]);

        Status::create([
            'name:en'   => 'On the way to you',
            'name:ar'   => 'فى الطريق اليك',
            'slug'      => 'on-way'
        ]);

        Status::create([
            'name:en'   => 'Delivered',
            'name:ar'   => 'تم التوصيل',
            'slug'      => 'delivered'
        ]);

        // $this->call("OthersTableSeeder");
    }
}
