<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminEmail = env('USER_ADMIN_EMAIL', 'admin@admin.com');
        $adminName = env('USER_ADMIN_NAME', 'administrator');
        \App\User::createOrUpdate([
            'name'=>$adminName,
            'email'=> $adminEmail,
            'password'=>bcrypt(env('USER_ADMIN_PASSWORD', '123456'))
        ], function($builder)use($adminEmail){
            return $builder->where('email', $adminEmail);
        });
    }
}
