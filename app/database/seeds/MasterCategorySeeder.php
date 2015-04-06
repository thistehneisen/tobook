<?php
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;

class MasterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'master' => 'Hiukset',
                'treatment' => [
                    'Kampaamopalvelut',
                    'Parturipalvelut',
                    'Värjäykset & raidat',
                    'Hiustenpidennykset',
                    'Hää- ja juhlakampaukset',
                ]
            ],
            [
                'master' => 'Karvanpoistot',
                'treatment' => [
                    'Vahaukset',
                    'Sokeroinnit',
                    'Laser-karvanpoistot',
                    'Parranajot',
                ]
            ],
            [
                'master' => 'Hieronnat',
                'treatment' => [
                'Klassiset hieronnat',
                'Urheiluhieronnat',
                'Thai-hieronnat',
                'Kuumakivihieronnat',
                'Intialaiset päähieronnat',
                ]
            ],
            [
                'master' => 'Jalkahoidot',
                'treatment' => [
                ]
            ],
            [
                'master' => 'Kasvohoidot',
                'treatment' => [
                    'Kasvohoito',
                    'Ihonpuhdistus',
                    'Terapiahoidot',
                ]
            ],
            [
                'master' => 'Vartalohoidot',
                'treatment' => [
                    'Laihdutushoito',
                    'Ihonhoito',
                    'Hemmotteluhoito',
                ]
            ],
            [
                'master' => 'Kynnet',
                'treatment' => [
                    'Manikyyrit',
                    'Pedikyyrit',
                    'Rakennekynnet',
                    'Geelikynnet',
                ]
            ],
            [
                'master' => 'Ripset & kulmat',
                'treatment' => [
                    'Ripsienpidennykset',
                    'Ripsienpidennysten huolto',
                    'Kulmien värjäykset ja muotoilut',
                ]
            ],
        ];

        if (MasterCategory::get()->isEmpty() === false) {
            $this->command->info('MasterCategorySeeder is already seeded');

            return;
        }

        $order = 1;
        foreach ($data as $item) {
            // Create new MasterCategory
            $category = new MasterCategory([
                'name' => $item['master'],
                'order' => $order++
            ]);
            $category->save();
            $category->saveMultilanguage(['fi' => $item['master']], ['fi' => '']);

            // Save TreatmentType
            foreach ($item['treatment'] as $name) {
                $treatment = new TreatmentType([
                    'name' => $name
                ]);
                $treatment->masterCategory()->associate($category);
                $treatment->save();
                $treatment->saveMultilanguage(['fi' => $name], ['fi' => '']);
            }
        }
    }
}
