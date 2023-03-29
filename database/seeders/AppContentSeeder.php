<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppContent;
use Carbon\Carbon;

class AppContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ContentStore=[
            [
                'identifier'=>'term_and_condition',
                'content'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry',
                'status'=>'1',
                'slug'=>'term_and_condition',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ],
            [
                'identifier'=>'privacy_policy',
                'content'=>'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to usin',
                'status'=>'1',
                'slug'=>'privacy_policy',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ],
        ];
        AppContent::insert($ContentStore);
    }
}
