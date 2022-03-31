<?php

namespace Database\Seeders;

use App\Models\EmailCc;
use Illuminate\Database\Seeder;

class EmailCCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailCc::factory()->count(5)->create();
    }
}
