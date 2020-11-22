<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(\App\Role::SUPER_ADMIN);
        $this->create(\App\Role::ADMIN);
        $this->create(\App\Role::EVENT_ORGANIZER);
        $this->create(\App\Role::PARTICIPANT);
    }

    /**
     * @param string $role
     */
    protected function create(string $role): void
    {
        \App\Role::createOrUpdate([
            'name' => $role
        ], function ($builder) use ($role) {
            return $builder->where('name', $role);
        });
    }
}
