<?php

namespace Database\Seeders;

use App\Models\EmailCc;
use Illuminate\Database\Seeder;

class EmailCC extends Seeder
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
