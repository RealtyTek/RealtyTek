<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title'=>'per mont',
                'slug'=>'per-mont',
                'description'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry',
                'duration'=>'1',
                'duration_unit'=>'year',
                'amount'=>'19.99',
                'trial_period'=>'60',
                'status'=>'1',
            ],
            [
                'title'=>'annual',
                'slug'=>'annual',
                'description'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry',
                'duration'=>'1',
                'duration_unit'=>'year',
                'amount'=>'99',
                'trial_period'=>'60',
                'status'=>'1',
            ],
        ];
        DB::table('subscription_packages')->insert($data);
    }
}
