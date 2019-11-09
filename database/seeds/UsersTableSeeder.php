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
        //
        DB::table('users')->insert([
            'account' => '18176812245',
            'name' => '叶斌龙',
            'email' => '594009702@qq.com',
            'phone' => '18176812245',
            'password' => bcrypt('yebinlong'),
            'grade' => '5',
        ]);
    }
}
