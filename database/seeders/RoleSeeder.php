<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Account',
                'link' => 'soa',
                'icon' => 'fa fa-user'
            ],
            [
                'name' => 'Arena Details',
                'link' => 'arena',
                'icon' => 'fas fa-landmark'
            ],
            [
                'name' => 'Summary Report',
                'link' => 'summary',
                'icon' => 'fa-book-reader'
            ],

            [
                'name' => 'User Management',
                'link' => 'users',
                'icon' => 'fa-users-cog'
            ],
            [
                'name' => 'Team Management',
                'link' => 'teams',
                'icon' => 'fa-users'
            ],
            [
                'name' => 'Configuration',
                'link' => 'detailsconfig',
                'icon' => 'fas fa-cogs'
            ],
            [
                'name' => 'Group Management',
                'link' => 'Group Management',
                'icon' => 'fad fa-box-open'
            ],
            [
                'name' => 'activitylogs',
                'link' => 'activitylogs',
                'icon' => 'fas fa-comments'
            ],


        ];



        foreach($roles as $role) {
            Role::create([
                    'name'  => $role['name'],
                    'link' => $role['link'],
                    'icon' =>  $role['icon'],
                ]);
        }
    }
}
