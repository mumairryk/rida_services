<?php

namespace Database\Seeders;

use App\Models\MenuItemType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['title' => 'Starter'],
            ['title' => 'Soups'],
            ['title' => 'Salads'],
            ['title' => 'Sea Food'],
            ['title' => 'Chicken'],
            ['title' => 'Beef'],
            ['title' => 'Lamb'],
            ['title' => 'Pork'],
            ['title' => 'Vegetarian'],
            ['title' => 'Noodles'],
            ['title' => 'Rice'],
            ['title' => 'Bread'],
            ['title' => 'Pasta'],
            ['title' => 'Pizza'],
            ['title' => 'Sandwich'],
            ['title' => 'Burger'],
            ['title' => 'Fries'],
            ['title' => 'Snacks'],
            ['title' => 'Appetizer'],
            ['title' => 'Dessert'],
            ['title' => 'Beverage'],
            ['title' => 'Other'],
        ];
        foreach ($types as $type) {
            MenuItemType::create($type);
        }
    }
}
