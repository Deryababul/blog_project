<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=['Eğlence','Bilişim','Sağlık','Teknoloji', 'Gezi','Spor','Günlük Yaşam'];
        foreach($categories as $category){
           Category::create([
                'name' => $category,
                'created_at' =>now(),
                'updated_at' =>now()
            ]);
        }
    }
}
