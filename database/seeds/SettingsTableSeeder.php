<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(
            [
            'title:en' => 'Trim', 
            'description:en' => 'Trim', 
            'copyrights:en' => 'All copyrights are reserved', 
            'title:ar' => 'تريم', 
            'description:ar' => 'تريم', 
            'copyrights:ar' => 'جميع الحقوق محفوظه', 
            'logo'  => 'logo.png',
            'icon'  => 'icon.png',
            ]);
    }
}
