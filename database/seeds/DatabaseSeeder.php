<?php

use Illuminate\Database\Seeder;
use Modules\Address\Database\Seeders\AddressDatabaseSeeder;
use Modules\Branch\Database\Seeders\BranchDatabaseSeeder;
use Modules\Captain\Database\Seeders\CaptainDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\City\Database\Seeders\CityDatabaseSeeder;
use Modules\Email\Database\Seeders\EmailDatabaseSeeder;
use Modules\Governorate\Database\Seeders\GovernorateDatabaseSeeder;
use Modules\Offer\Database\Seeders\OfferDatabaseSeeder;
use Modules\Phone\Database\Seeders\PhoneDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Salon\Database\Seeders\SalonDatabaseSeeder;
use Modules\Social\Database\Seeders\SocialDatabaseSeeder;
use Modules\Status\Database\Seeders\StatusDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GovernorateDatabaseSeeder::class,
            CityDatabaseSeeder::class,
            CategoryDatabaseSeeder::class,
            SettingsTableSeeder::class,
            PermissionsTableSeeder::class,
            CaptainDatabaseSeeder::class,
            SalonDatabaseSeeder::class,
            BranchDatabaseSeeder::class,
            AddressDatabaseSeeder::class,
            SocialDatabaseSeeder::class,
            EmailDatabaseSeeder::class,
            PhoneDatabaseSeeder::class,
            ProductDatabaseSeeder::class,
            OfferDatabaseSeeder::class,
            StatusDatabaseSeeder::class
        ]);
    }
}
