<?php

class DatabaseSeeder extends Seeder
{
    /**
	 * Run the database seeds.
	 *
	 * @return void
	 */
    public function run()
    {
        Eloquent::unguard();

        $this->call('EntrustSeeder');
        $this->call('ModuleSeeder');
        $this->call('BusinessCategorySeeder');
        $this->call('MasterCategorySeeder');
    }

}
