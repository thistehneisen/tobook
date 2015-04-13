<?php namespace App\Core\Commands;

use App\Consumers\Models\Consumer;
use App\Core\Models\Role;
use App\Core\Models\User;
use Illuminate\Console\Command;

class ConnectConsumersCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:connect-consumers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect consumers not having consumers data to their corresponding info';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Get all consumers not having consumer data
        $users = User::has('consumer', 0)
            ->whereHas('roles', function ($q) {
                $q->where('name', Role::CONSUMER);
            })
            ->get();

        foreach ($users as $user) {
            $consumer = Consumer::where('email', $user->email)->first();
            if ($consumer !== null) {
                $user->consumer()->associate($consumer);
                $user->save();

                $this->output->writeln($user->email);
            }
        }

        $this->info('Done');
    }
}
