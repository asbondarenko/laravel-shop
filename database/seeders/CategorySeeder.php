<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private const FAKER_RECORDS_COUNT = 20;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(self::FAKER_RECORDS_COUNT)->create();
    }
}
