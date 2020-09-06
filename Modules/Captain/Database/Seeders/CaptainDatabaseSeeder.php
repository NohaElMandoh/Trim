<?php

namespace Modules\Captain\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Vehicle\Entities\Vehicle;
use Modules\City\Entities\City;
use Modules\Governorate\Entities\Governorate;

class CaptainDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $role = config('permission.models.role')::where('name', 'captain')->firstOrFail();

        $user = User::create([
            'name'      => 'Cap Mohamed',
            'phone'     => '01222222222',
            'email'     => 'cap@cap.com',
            'password'  => bcrypt('123456'),
            'image'     => 'shop.png',
            'id_photo'  => 'shop.png',
            'is_active' => 1,
            'city_id'   => City::first()->id,
            'governorate_id'   => Governorate::first()->id,
        ]);

        $user->roles()->attach($role);

        // $this->call("OthersTableSeeder");
    }
}
