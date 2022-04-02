<?php

namespace Database\Seeders;

use App\Models\OperatorGroups;
use Illuminate\Database\Seeder;

class OperatorGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OperatorGroups::factory()->count(5)->create();
    }
}
