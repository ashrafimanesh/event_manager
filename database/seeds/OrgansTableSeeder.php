<?php

use Illuminate\Database\Seeder;

class OrgansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Organ::createOrUpdate([
            'name'=>\App\Organ::DEFAULT
        ], function($builder){
            return $builder->where('name', \App\Organ::DEFAULT);
        });
    }
}
