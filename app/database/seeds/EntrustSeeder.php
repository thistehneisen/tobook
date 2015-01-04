<?php
use App\Core\Models\Permission;
use App\Core\Models\Role;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // We'll not seed if there is existing data
        $all = Permission::all();

        if ($all->isEmpty() === false) {
            $this->command->info('EntrustSeeder is already seeded');

            return;
        }

        $permissions = [
            'super_user' => 'Super User'
        ];
        $permissionMap = [];

        foreach ($permissions as $name => $display_name) {
            $permission = new Permission();
            $permission->name = $name;
            $permission->display_name = $display_name;
            try {
                if ($permission->save()) {
                    $permissionMap[$name] = $permission;
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                continue;
            }
        }

        $roles = [
            'User' => [],
            'Admin' => ['super_user'],
            'Consumer' => [],
        ];
        foreach ($roles as $name => $permissions) {
            try {
                $role = new Role();
                $role->name = $name;
                $role->save();

                // Sync permissions
                $ids = [];
                foreach ($permissions as $key) {
                    if (isset($permissionMap[$key])) {
                        $permission = $permissionMap[$key];
                        $ids[] = $permission->id;
                    }
                }

                if (!empty($ids)) {
                    $role->perms()->sync($ids);
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                continue;
            }
        }
    }

}
