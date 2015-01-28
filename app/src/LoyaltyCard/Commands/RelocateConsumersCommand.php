<?php namespace App\LoyaltyCard\Commands;

use Illuminate\Console\Command;
use App\LoyaltyCard\Models\Transaction;
use App\LoyaltyCard\Models\Consumer;

class RelocateConsumersCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:relocate-lc-consumers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link consumer IDs in lc_transactions to core consumers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $transactions = Transaction::all();
        foreach ($transactions as $transaction) {
            $consumer = Consumer::find($transaction->consumer_id);
            if ($consumer === null) {
                continue;
            }

            $transaction->consumer_id = $consumer->consumer_id;
            $transaction->save();
            $this->output->write('.');
        }
    }
}
