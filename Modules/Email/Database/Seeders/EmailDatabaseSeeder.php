<?php

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Email\Entities\Email;

class EmailDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Email::create([
            'email'     => 'info@trim.style',
            'order'     => 1
        ]);
        // $this->call("OthersTableSeeder");
    }
}
