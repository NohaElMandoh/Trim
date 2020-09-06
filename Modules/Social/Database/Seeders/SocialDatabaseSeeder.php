<?php

namespace Modules\Social\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Social\Entities\Social;

class SocialDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Social::create([
            'order' => 1,
            'image' => 'facebook.png',
            'url'   => 'https://facebook.com/oq'
        ]);

        Social::create([
            'order' => 2,
            'image' => 'twitter.png',
            'url'   => 'https://twitter.com/oq'
        ]);
        // $this->call("OthersTableSeeder");
    }
}
