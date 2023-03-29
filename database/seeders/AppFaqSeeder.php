<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppFaq;
use Carbon\Carbon;

class AppFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $AppFaqsStore=[
            [
                'question'=>'is simply dummy text of the printing and typesetting industry?',
                'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry..',
                'slug'=>'11001',
                'status'=>'1',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ],
            [
                'question'=>'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout?',
                'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry..',
                'status'=>'1',
                'slug'=>'11002',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ],
            [
                'question'=>'It is distracted by the readable content of a page when looking at its layout?',
                'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry..',
                'status'=>'1',
                'slug'=>'11003',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ],
        ];
        AppFaq::insert($AppFaqsStore);
    }
}
