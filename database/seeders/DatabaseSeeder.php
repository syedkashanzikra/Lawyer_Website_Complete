<?php

namespace Database\Seeders;

use App\Models\Utility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if(Request::route()->getName() != 'LaravelUpdater::database')
        {
            $this->call(UserSeeder::class);
            $this->call(CountrySeeder::class);
            $this->call(StateSeeder::class);
            $this->call(CitySeeder::class);
            $this->call(PlansTableSeeder::class);
            $this->call(MotionSeeder::class);
            $this->call(DocTypeSeeder::class);
            $this->call(CustomFieldSeeder::class);

            Artisan::call('module:migrate LandingPage');
            Artisan::call('module:seed LandingPage');

        }else{
            Utility::languagecreate();
        }
    }
}
