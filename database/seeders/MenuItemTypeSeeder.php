<?php

namespace Database\Seeders;

use App\Models\MenuItemType;
use Illuminate\Database\Seeder;

class MenuItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuItemType::create([
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
        ]);
    }
}
