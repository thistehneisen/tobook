<?php namespace App\LoyaltyCard\Commands;
use DB;
use Illuminate\Console\Command;
use App\LoyaltyCard\Models\Offer as OfferModel;

class MoveOffersCommand extends Command
{
    protected $name = 'varaa:move-offers';
    protected $description = 'Move offers of loyalty card from old tables to new one';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('');

        $result = DB::table('tbl_loyalty_stamp')->get();
        $this->info('Moving '.count($result).' offers to the new tables');

        DB::setTablePrefix($oldPrefix);

        foreach ($result as $item) {
            $offer = new OfferModel;
            $offer->unguard();

            $offer->fill([
                'id'            => $item->loyalty_stamp,
                'user_id'       => $item->owner,
                'name'          => $item->stamp_name,
                'required'      => $item->cnt_required,
                'total_used'    => 0,
                'free_service'  => $item->cnt_free,
                'is_active'     => $item->valid_yn === 'Y' ? true : false,
                'is_auto_add'   => $item->auto_add_yn === 'Y' ? true : false,
                'created_at'    => $item->created_time,
                'updated_at'    => $item->updated_time,
            ]);

            $offer->reguard();

            if ($offer->save()) {
                echo "\t{$item->stamp_name}\t\t";
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
