<?php

use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->insert([
            [
                'name'=>str_random(10),
                'address'=>str_random(100).'@gmail.com',
                'age'=>25,
                'photo'=>'No Image',
                'created_at'=> new DateTime()
            ],
            [
                'name'=>str_random(10),
                'address'=>str_random(100).'@gmail.com',
                'age'=>24,
                'photo'=>'No Image',
                'created_at'=> new DateTime()
            ],
            [
                'name'=>str_random(10),
                'address'=>str_random(100).'@gmail.com',
                'age'=>23,
                'photo'=>'No Image',
                'created_at'=> new DateTime()
            ],
            [
                'name'=>str_random(10),
                'address'=>str_random(100).'@gmail.com',
                'age'=>22,
                'photo'=>'No Image',
                'created_at'=> new DateTime()
            ],
        ]);
    }
}
