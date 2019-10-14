<?php

use Illuminate\Database\Seeder;

class Profession_codeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('professions_code')->insert([
            'code' => '04413',
            'profession' => '电子信息工程',
            'college' => '电子与信息工程学院',
        ]);
    }
}
