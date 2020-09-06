<?php

namespace Modules\Salon\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Vehicle\Entities\Vehicle;
use Modules\City\Entities\City;
use Modules\Governorate\Entities\Governorate;

class SalonDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $role = config('permission.models.role')::where('name', 'salon')->firstOrFail();

        $user = User::create([
            'name'      => 'Salon Mohamed',
            'phone'     => '0111111111',
            'email'     => 'salon@salon.com',
            'password'  => bcrypt('123456'),
            'image'     => 'salon.png',
            'commercial_register'  => 'salon.png',
            'is_active' => 1,
            'description' => 'Salon Mohamed',
            'city_id'   => City::first()->id,
            'governorate_id'   => Governorate::first()->id,
        ]);

        $user->roles()->attach($role);

        // $this->call("OthersTableSeeder");
    }
}
