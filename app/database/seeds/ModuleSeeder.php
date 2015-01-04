<?php
use Carbon\Carbon;
use App\Core\Models\Module;
use App\Core\Models\User;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all = Module::all();
        if ($all->isEmpty() === false) {
            $this->command->info('ModuleSeeder is already seeded');

            return;
        }

        $data = [
            ['name' => 'appointment', 'uri' => '/services/appointment-scheduler'],
            ['name' => 'cashier', 'uri' => '/services/cashier'],
            ['name' => 'restaurant', 'uri' => '/services/restaurant-booking'],
            ['name' => 'timeslot', 'uri' => '/services/timeslot'],
            ['name' => 'loyalty', 'uri' => '/services/loyalty-program'],
        ];

        $modules = [];
        foreach ($data as $item) {
            if (Module::where('name', $item['name'])->first()) {
                // do not make duplicates
                continue;
            }

            $module = new Module($item);
            $module->save();

            $modules[] = $module->id;
        }

        // TODO fix this
        /*
        // All users
        $users = User::all();
        $start = Carbon::now();
        $end   = Carbon::now()->addYears(100);

        foreach ($users as $user) {
            echo "Now processing {$user->email}...\n";
            $user->modules()->attach($modules, [
                'start'     => $start,
                'end'       => $end,
                'is_active' => true
            ]);
        }
        */
    }

}
