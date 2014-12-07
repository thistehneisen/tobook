<?php namespace App\Consumers;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\User;
use DB;

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
     * List of processed consumers
     *
     * @var array
     */
    protected $processed = [];

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

        $noPhoneCounter = [];

        $user = User::findOrFail($userId);
        $this->info('Merging consumers of user '.$userId);
        $consumers = $user->consumers()
            ->where('is_visible', 1)
            ->orderBy('id', 'ASC')
            ->get();
        foreach ($consumers as $consumer) {
            // If this consumer is already processed, skip
            if (isset($this->processed[$consumer->id])) {
                continue;
            }

            // Also skip who doesn't have phone
            if (empty($consumer->phone)) {
                $noPhoneCounter[] = $consumer;
                continue;
            }

            // Find other consumers that have the same first name and last name
            $list = $user->consumers()->where('first_name', $consumer->first_name)
                ->where('last_name', $consumer->last_name)
                ->where('id', '!=', $consumer->id)
                ->get();

            if ($list->isEmpty() === false) {
                $this->info(sprintf('Found %d: %s %s (%s)',
                    $list->count(),
                    $consumer->first_name,
                    $consumer->last_name,
                    $consumer->phone));

                // Relocate consumer
                foreach ($list as $duplicated) {
                    // Mark as processed
                    $this->processed[$duplicated->id] = true;
                    $this->relocate($consumer, $duplicated);
                }
            }
        }

        $this->question('There are '.count($noPhoneCounter).' consumers without phone numbers:');
        foreach ($noPhoneCounter as $consumer) {
            $this->line(sprintf(
                "\t %d %s %s %s",
                $consumer->id,
                $consumer->first_name,
                $consumer->last_name,
                $consumer->phone
            ));
        }
    }

    /**
     * Relocate a duplicated consumer
     *
     * @param App\Consumers\Models\Consumer $consumer
     * @param App\Consumers\Models\Consumer $duplicated
     *
     * @return void
     */
    protected function relocate($consumer, $duplicated)
    {
        // Compare two phone numbers, if they're not similar, then just quit
        if ($this->isSimilarPhoneNumber($consumer->phone, $duplicated->phone) === false) {
            return;
        }

        // *sign* Time to merge
        $this->comment(sprintf("\tRelocating %d (%s) -> %d", $duplicated->id, $duplicated->phone, $consumer->id));

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
            ->where('user_id', $consumer->id)
            ->update(['is_visible' => 0]);
    }

    protected function isSimilarPhoneNumber($a, $b)
    {
        // First we'll trim and remove non-numeric characters in both arguments
        $a = preg_replace('/[^0-9]+/', '', $a);
        $b = preg_replace('/[^0-9]+/', '', $b);

        // Then test if the longer phone number contain the shorter phone number
        $shorter = $a;
        $longer  = $b;

        if (strlen($a) > strlen($b)) {
            $shorter = $b;
            $longer = $a;
        }

        return str_contains($longer, $shorter);
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
