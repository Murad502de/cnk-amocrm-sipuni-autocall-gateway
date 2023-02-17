<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
            'access_token' => 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ',
            'uuid'         => 'c8491983-75b4-4e1f-af2c-b194c7c71ab1',
            'email'        => 'leshkinkotkz@gmail.com',
            'password'     => '8ac3ba0e2a4d915edd393ea64d1cca23',
        ]);
    }
}
