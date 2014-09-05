<?php
use App\Core\Models\BusinessCategory;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'beauty'],
            ['name' => 'restaurant'],
            ['name' => 'car'],
            ['name' => 'wellness'],
            ['name' => 'activities'],
        ];

        foreach ($items as $item) {
            $category = new BusinessCategory($item);
            $category->save();
        }
        echo 'Done';
    }
}
