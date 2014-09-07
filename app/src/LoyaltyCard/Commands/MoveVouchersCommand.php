<?php
use Config, DB;
use Illuminate\Console\Command;
use App\LoyaltyCard\Models\Voucher as VoucherModel;

class MoveVouchersCommand extends Command
{
    protected $name = 'varaa:move-vouchers';
    protected $description = 'Move vouchers of loyalty card from old tables to new one';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('');

        $result = DB::table('tbl_loyalty_point')->get();
        $this->info('Moving '.count($result).' vouchers to the new tables');

        DB::setTablePrefix($oldPrefix);

        foreach ($result as $item) {
            $voucher = new VoucherModel;
            $voucher->unguard();

            $voucher->fill([
                'id'            => $item->loyalty_stamp,
                'user_id'       => $item->owner,
                'name'          => $item->point_name,
                'required'      => $item->score_required,
                // 'total_used'    => $item->,
                'value'         => $item->discount,
                'type'          => $item->
                'is_active'     => $item->valid_yn,
                // 'is_auto_add'   => $item->,
                'created_at'    => $item->created_time,
                'updated_at'    => $item->updated_time,
            ]);

            $voucher->reguard();

            if ($voucher->save()) {
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
