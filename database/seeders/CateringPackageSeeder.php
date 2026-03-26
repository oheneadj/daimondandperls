<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CateringPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Rice Dishes', 'slug' => 'rice'],
            ['name' => 'Banku & Fufu', 'slug' => 'banku'],
            ['name' => 'Grills', 'slug' => 'grills'],
            ['name' => 'Soups & Stews', 'slug' => 'soups'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $riceCat = Category::where('slug', 'rice')->first();
        $bankuCat = Category::where('slug', 'banku')->first();
        $grillsCat = Category::where('slug', 'grills')->first();
        $soupsCat = Category::where('slug', 'soups')->first();

        $packages = [
            [
                'category_id' => $riceCat->id,
                'name' => 'Jollof Rice Package',
                'description' => 'Perfectly spiced party jollof served with your choice of protein and sides.',
                'price' => 85,
                'min_guests' => 50,
                'is_popular' => true,
                'features' => ['Party jollof rice', 'Grilled chicken or beef', 'Fried plantain (kelewele)', 'Garden salad', 'Assorted drinks', 'Serving staff'],
                'sort_order' => 1,
            ],
            [
                'category_id' => $bankuCat->id,
                'name' => 'Banku & Tilapia',
                'description' => 'Classic Ghanaian banku with whole grilled tilapia and spicy pepper sauce.',
                'price' => 70,
                'min_guests' => 50,
                'is_popular' => false,
                'features' => ['Banku (corn & cassava)', 'Whole grilled tilapia', 'Pepper sauce (shito)', 'Onions & tomatoes', 'Kontomire stew', 'Serving staff'],
                'sort_order' => 2,
            ],
            [
                'category_id' => $riceCat->id,
                'name' => 'Waakye Special',
                'description' => 'Traditional waakye (rice & beans) with all the classic street-style toppings.',
                'price' => 65,
                'min_guests' => 50,
                'is_popular' => false,
                'features' => ['Waakye (rice & beans)', 'Wele (cow skin)', 'Spaghetti', 'Fried fish or chicken', 'Shito & pepper sauce', 'Hard boiled eggs'],
                'sort_order' => 3,
            ],
            [
                'category_id' => $bankuCat->id,
                'name' => 'Fufu & Soup Package',
                'description' => 'Authentic fufu served with a variety of rich Ghanaian soups and assorted meats.',
                'price' => 90,
                'min_guests' => 80,
                'is_popular' => false,
                'features' => ['Fufu (cassava & plantain)', 'Choice of 2 soups', 'Goat meat or chicken', 'Assorted fish', 'Kontomire stew', 'Serving staff'],
                'sort_order' => 4,
            ],
            [
                'category_id' => $grillsCat->id,
                'name' => 'Grills & BBQ Package',
                'description' => 'A premium selection of live-grilled meats and seafood for an energetic event.',
                'price' => 120,
                'min_guests' => 100,
                'is_popular' => false,
                'features' => ['Grilled whole tilapia', 'BBQ chicken quarters', 'Grilled lamb chops', 'Kelewele & yam', 'Pepper sauces', 'Live grill station + chef'],
                'sort_order' => 5,
            ],
            [
                'category_id' => $soupsCat->id,
                'name' => 'Kontomire Stew Pack',
                'description' => 'Rich, nutritious kontomire stew served with traditional starches.',
                'price' => 60,
                'min_guests' => 50,
                'is_popular' => false,
                'features' => ['Kontomire / palava sauce', 'Boiled yam or rice', 'Fried fish or egg', 'Boiled plantain', 'Shito on the side', 'Serving staff'],
                'sort_order' => 6,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(
                ['name' => $pkg['name']],
                array_merge($pkg, [
                    'slug' => Str::slug($pkg['name']),
                    'is_active' => true,
                ])
            );
        }
    }
}
