<?php namespace App\Consumers\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\User;
use DB, Util;

class MergeComsumersCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:merge-consumers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge duplicated consumers of the given user, or all users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $userId = $this->argument('id');
        if ($userId === null) {
            // @todo: Handle later
            return;
        }

        if ($userId === 'all') {
            $users =  DB::table('users')->join('businesses', 'businesses.user_id', '=','users.id')
                ->select('users.id')
                ->whereNull('users.deleted_at')->get();

            foreach ($users as $user) {
                $this->merge($user->id);
            }

        } else {
            $this->merge($userId);
        }
    }

    protected function merge($userId)
    {
        $user = User::findOrFail($userId);
        $this->mergeConsumersHavingPhone($user);
        $this->mergeConsumersHavingNoPhone($user);
    }

    protected function mergeConsumersHavingPhone($user)
    {
        $processed = [];
        $this->info('Merging consumers of user '.$user->id);

        $consumers = $user->consumers()
            ->where(function ($query) {
                return $query->where('consumers.phone', '!=', '')
                    ->orWhereNotNull('consumers.phone');
            })
            ->orderBy('id', 'ASC')
            ->get();

        foreach ($consumers as $consumer) {
            // If this consumer is already processed, skip
            if (isset($processed[$consumer->id])) {
                continue;
            }

            // Also skip who doesn't have phone
            if (empty($consumer->phone)) {
                $noPhoneConsumers[] = $consumer;
                continue;
            }

            // Find other consumers that have the same first name and last name
            $list = $user->consumers()->where('consumers.first_name', $consumer->first_name)
                ->where('consumers.last_name', $consumer->last_name)
                ->where('consumers.id', '!=', $consumer->id)
                ->get();

            if ($list->isEmpty() === false) {
                $this->info(sprintf('Found %d: %s %s (%s)',
                    $list->count(),
                    $consumer->first_name,
                    $consumer->last_name,
                    $consumer->phone));

                // Relocate consumer
                foreach ($list as $duplicated) {
                    // Compare two phone numbers, if they're not similar, then just quit
                    if (!Util::isSimilarPhoneNumber(
                        $consumer->phone,
                        $duplicated->phone
                    )) {
                        continue;
                    }

                    // Mark as processed
                    $processed[$duplicated->id] = true;
                    $this->relocate($user, $consumer, $duplicated);
                }
            }
        }
    }

    protected function mergeConsumersHavingNoPhone($user)
    {
        $processed = [];

        $noPhoneConsumers = $user->consumers()->where('consumers.phone', '')
            ->orWhereNull('consumers.phone')
            ->orderBy('consumers.id', 'asc')
            ->get();

        $this->question('There are '.count($noPhoneConsumers).' consumers having no phone numbers:');
        foreach ($noPhoneConsumers as $consumer) {
            if (isset($processed[$consumer->id])) {
                continue;
            }

            $list = $user->consumers()
                ->where('consumers.first_name', $consumer->first_name)
                ->where('consumers.last_name', $consumer->last_name)
                ->where('consumers.id', '!=', $consumer->id)
                ->where(function ($query) {
                    return $query->where('consumers.phone', '')
                        ->orWhereNull('consumers.phone');
                })
                ->orderBy('consumers.id', 'asc')
                ->get();

            $this->info(sprintf('Found %d: %s %s',
                $list->count(),
                $consumer->first_name,
                $consumer->last_name));

            if ($list->isEmpty() === false) {
                foreach ($list as $duplicated) {
                    $processed[$duplicated->id] = true;
                    $this->relocate($user, $consumer, $duplicated);
                }
            }
        }
    }

    /**
     * Relocate a duplicated consumer
     *
     * @param App\Consumers\Models\User     $user
     * @param App\Consumers\Models\Consumer $consumer
     * @param App\Consumers\Models\Consumer $duplicated
     *
     * @return void
     */
    protected function relocate($user, $consumer, $duplicated)
    {
        // *sign* Time to merge
        $this->comment(sprintf("\tRelocating %d \t (%s) -> %d", $duplicated->id, $duplicated->phone, $consumer->id));

        $tables = [
            'lc_transactions',
            'as_bookings',
            'mt_group_consumers',
            'carts',
            'mt_historys',
        ];
        foreach ($tables as $table) {
            DB::table($table)->where('consumer_id', $duplicated->id)
                ->update(['consumer_id' => $consumer->id]);
        }

        // Break the chain
        DB::table('consumer_user')->where('consumer_id', $duplicated->id)
            ->where('user_id', $user->id)
            ->delete();
    }



    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::OPTIONAL, 'ID of the user']
        ];
    }
}
