<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            CmsSeeder::class,
            AppContentSeeder::class,
            AppFaqSeeder::class,
            SubscriptionSeeder::class
        ]);
    }
}
