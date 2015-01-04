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
        $all = BusinessCategory::all();
        if ($all->isEmpty() === false) {
            $this->command->info('BusinessCategorySeeder is already seeded');

            return;
        }

        $items = [
            [
                'name' => 'beauty_hair',
                'sub' => [
                    ['name' => 'beautysalon', 'keywords' => 'facial treatment, feet treatment, eyelash extension, waxing, sugaring,hair removal, pedicure, manicure, body treatment, beauty, make up'],
                    ['name' => 'nails', 'keywords' => 'manicure, pedicure'],
                    ['name' => 'hairdresser', 'keywords' => 'Hair cut, hair coloring, hair styling, hair extensions'],
                ]
            ],
            [
                'name' => 'restaurant',
                'sub' => [
                    ['name' => 'Fine dining', 'keywords' => 'steak, fine dining'],
                    ['name' => 'Nepalese', 'keywords' => '' ],
                    ['name' => 'Traditional', 'keywords' => '' ],
                    ['name' => 'Sushi', 'keywords' => '' ],
                    ['name' => 'Thai', 'keywords' => '' ],
                    ['name' => 'Italian', 'keywords' => '' ],
                    ['name' => 'Grill', 'keywords' => '' ],
                    ['name' => 'Chinese', 'keywords' => '' ],
                ]
            ],
            [
                'name' => 'car',
                'sub' => [
                    ['name' => 'Car wash','keywords' => 'car detailing, car cleaning, car wash'],
                    ['name' => 'Car service','keywords' => 'tire rotation, car service, oil change, window shield repair, cool system flush'],
                    ['name' => 'Bike service','keywords' => 'bike maintenance, bike repair'],
                ]
            ],
            [
                'name' => 'wellness',
                'sub' => [
                    ['name' => 'Physical theraphy', 'keywords' => ''],
                    ['name' => 'Massage', 'keywords' => 'hot stone massage, massage, '],
                    ['name' => 'Dentist', 'keywords' => 'dental care, '],
                    ['name' => 'Acupuncture', 'keywords' => 'acupuncture, chinese wellness'],
                    ['name' => 'Chiropractic treatment', 'keywords' => 'back problem, chiropractitian'],
                    ['name' => 'Teeth whitening', 'keywords' => ''],
                ]
            ],
            [
                'name' => 'activities',
                'sub' => [
                    ['name' => 'Bowling'],
                    ['name' => 'Karting'],
                    ['name' => 'Gym'],
                    ['name' => 'Dance'],
                    ['name' => 'Badminton'],
                    ['name' => 'Tennis'],
                    ['name' => 'Personal training'],
                    ['name' => 'Yoga'],
                ]
            ],
            [
                'name' => 'Home',
                'sub' => [
                    ['name' => 'House cleaning'],
                    ['name' => 'Handyman'],
                    ['name' => 'Photography'],
                    ['name' => 'Babysitting'],
                    ['name' => 'Snow removal'],
                ]
            ],
        ];

        foreach ($items as $item) {
            if (BusinessCategory::where('name', $item['name'])->first()) {
                // do not make duplicates
                continue;
            }

            $parent = new BusinessCategory(['name' => $item['name']]);
            $parent->save();

            foreach ($item['sub'] as $child) {
                $category = new BusinessCategory($child);
                $category->parent()->associate($parent);
                $category->save();
            }
        }

        echo 'Done';
    }
}
