<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
		Schema::enableForeignKeyConstraints();
		
		User::insert([
			[
				'name' => 'Admin User',
				'email' => 'admin@example.com',
				'password' => Hash::make('password'),
				'role' => 'reviewer',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]
		]);
    }
}
