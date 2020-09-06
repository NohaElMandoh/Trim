<?php

namespace Modules\Phone\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Phone\Entities\Phone;

class PhoneDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Phone::create([
            'phone' => '01010101010',
            'order' => 1
        ]);

        // $this->call("OthersTableSeeder");
    }
}
