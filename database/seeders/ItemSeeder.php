<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'name' => 'proyektor',
            'code' => 'pre001',
            'price' => '1000000',
            'category_id' => '1',
            'condition' => 'baik',
            'status' => 'tersedia',
            'deleted_at' => null
        ]);
    }
}
