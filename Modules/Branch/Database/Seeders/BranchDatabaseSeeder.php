<?php

namespace Modules\Branch\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Entities\Branch;

class BranchDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Branch::create([
            'address:en'    => 'Tolba ouda street, Zagazig, Egypt',
            'address:ar'    => 'شارع طلبه عويضه ,الزقازيق, مصر',
            'lat'           => 30.58768,
            'lng'           => 31.502,
            'user_id'       => User::role('salon')->first()->id,
        ]);

        // $this->call("OthersTableSeeder");
    }
}
