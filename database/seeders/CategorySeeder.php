<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name'=>'Neurological medications',
        ]);
        Category::create([
            'name'=>'Heart medications',
        ]);
        Category::create([
            'name'=>'Anti-inflammatories',
        ]);
        Category::create([
            'name'=>'Food supplements',
        ]); Category::create([
            'name'=>'Painkillers',
        ]);

    }
}
