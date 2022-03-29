<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            1,
            2,
            3,
            4,
            5,
            6
        ];

        $user = [
            1,
            2
        ];
        
        foreach($user as $user_id ){
            foreach($permissions as $permission) {
                Permission::create([
                    'user_id' => $user_id,
                    'role_id'  => $permission,
                ]);
            }
        }
       
    }
}
