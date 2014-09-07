<?php
use Config, DB;
use Illuminate\Console\Command;
use App\LoyaltyCard\Models\Consumer as ConsumerModel;
use App\Consumers\Models\Consumer as Core;

class MoveConsumersCommand extends Command
{
    protected $name = 'varaa:move-consumers';
    protected $description = 'Move consumers of loyalty card from old tables to new one';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('');

        $result = DB::table('tbl_loyalty_consumer')->get();
        $this->info('Moving '.count($result).' consumers to the new tables');

        DB::setTablePrefix($oldPrefix);

        foreach ($result as $item) {
            $core = new Core;
            $consumer = new ConsumerModel;
            $core->unguard();
            $consumer->unguard();
            $core->fill([
                'id'            => $item->loyalty_consumer,
                'user_id'       => $item->owner,
                'first_name'    => $item->first_name,
                'last_name'     => $item->last_name,
                'email'         => $item->email,
                'phone'         => $item->phone,
                'address'       => $item->address1,
                // 'postcode'      => $item->postcode,
                'city'          => $item->city,
                // 'country'       => $item->country,
                'created_at'    => $item->created_time,
                'updated_at'    => $item->updated_time,
            ]);

            $consumer->fill([
                'id'            => $item->loyalty_consumer,
                'consumer_id'   => $item->owner,
                'total_points'  => $item->current_score,
            ]);

            $core->reguard();
            $consumer->reguard();

            if ($core->save() && $consumer->save()) {
                $this->info('DONE');
            }
        }
    }

    protected function getArguments()
    {
        return [];
    }

    protected function getOptions()
    {
        return [];
    }
}
