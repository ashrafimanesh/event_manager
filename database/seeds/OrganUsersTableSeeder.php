<?php

use Illuminate\Database\Seeder;

class OrganUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminEmail = env('USER_ADMIN_EMAIL', 'admin@admin.com');
        $user = \App\User::query()->where(['email'=>$adminEmail])->first();
        $organ = \App\Organ::query()->where(['name'=>\App\Organ::DEFAULT])->first();
        if(!$user || !$organ){
            return;
        }
        $organUser = \App\OrganUser::createOrUpdate([
            'user_id'=>$user->id,
            'organ_id'=>$organ->id
        ], function($builder)use($user, $organ){
            return $builder->where(['user_id'=>$user->id, 'organ_id'=>$organ->id]);
        });

        $this->addRole($organUser);
    }

    /**
     * @param $organUser
     */
    protected function addRole($organUser)
    {
        $role = \App\Role::query()->where('name', \App\Role::SUPER_ADMIN)->first();
        if (!$role) {
            return;
        }
        \App\OrganUserRole::createOrUpdate([
            'organ_user_id' => $organUser->id,
            'role_name'=>\App\Role::SUPER_ADMIN,
            'role_id' => $role->id
        ], function ($builder) use ($organUser, $role) {
            return $builder->where([
                'organ_user_id' => $organUser->id,
                'role_id' => $role->id
            ]);
        });
    }
}
