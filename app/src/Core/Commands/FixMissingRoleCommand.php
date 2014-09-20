<?php namespace App\Core\Commands;

use DB;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Console\Command;
use App\Core\Models\User;
use App\Core\Models\Role;

class FixMissingRoleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:fix-missing-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing roles of newly registered users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $role = Role::user();
        if (!$role) {
            $this->error('Cannot find role User');
            exit;
        }

        $users = DB::table('users')
            ->select('id')
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('assigned_roles')
                    ->whereRaw('varaa_users.id = varaa_assigned_roles.user_id');
            })
            ->get();

        foreach ($users as $user) {
            DB::table('assigned_roles')
            ->insert([
                'user_id' => $user->id,
                'role_id' => $role->id
            ]);
            echo '.';
        }

        $this->info('Done');
    }
}
