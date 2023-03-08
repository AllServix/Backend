<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->insert([
            ['service' => ('barberia'), 'price' => 20 ],
            ['service' => ('tinte'), 'price' => 30],
            ['service' => ('mechas'), 'price' => 40],
            ['service' => ('corte'), 'price' => 15],
            ['service' => ('cejas'), 'price' => 10]
        ]);
    }
}
