<?php namespace App\LoyaltyCard\Commands;
use DB;
use Illuminate\Console\Command;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Consumer as ConsumerModel;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Transaction as TransactionModel;

class MigrateLoyaltyCardCommand extends Command
{
    protected $name = 'varaa:move-lc';
    protected $description = 'Move loyalty card information from old tables to new one';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $oldPrefix = DB::getTablePrefix();

        // Migrating Offers
        // $totalUsageOfOffer = [];
        // DB::setTablePrefix('');
        // $result = DB::table('tbl_loyalty_stamp')->get();
        // $this->info('Moving '.count($result).' offers to the new tables');

        // DB::setTablePrefix($oldPrefix);

        // foreach ($result as $item) {
        //     $offer = new OfferModel;
        //     $offer->unguard();

        //     $offer->fill([
        //         'id'            => $item->loyalty_stamp,
        //         'user_id'       => $item->owner,
        //         'name'          => $item->stamp_name,
        //         'required'      => $item->cnt_required,
        //         'total_used'    => 0,
        //         'free_service'  => $item->cnt_free,
        //         'is_active'     => $item->valid_yn === 'Y' ? true : false,
        //         'is_auto_add'   => $item->auto_add_yn === 'Y' ? true : false,
        //         'created_at'    => $item->created_time,
        //         'updated_at'    => $item->updated_time,
        //         ]);

        //     $offer->reguard();

        //     if ($offer->save()) {
        //         echo "\t{$item->stamp_name}\t\t";
        //         $this->info('DONE');
        //     }
        // }

        // Migrating Voucher
        // $totalUsageOfVoucher = [];
        // DB::setTablePrefix('');
        // $result = DB::table('tbl_loyalty_point')->get();
        // $this->info('Moving '.count($result).' vouchers to the new tables');

        // DB::setTablePrefix($oldPrefix);

        // foreach ($result as $item) {
        //     $voucher = new VoucherModel;
        //     $voucher->unguard();

        //     $voucher->fill([
        //         'id'            => $item->loyalty_point,
        //         'user_id'       => $item->owner,
        //         'name'          => $item->point_name,
        //         'required'      => $item->score_required,
        //         'total_used'    => 0,
        //         'value'         => $item->discount,
        //         'type'          => 'Percent',
        //         'is_active'     => $item->valid_yn === 'Y' ? true : false,
        //         'created_at'    => $item->created_time,
        //         'updated_at'    => $item->updated_time,
        //     ]);

        //     $voucher->reguard();

        //     if ($voucher->save()) {
        //         echo "\t{$item->point_name}\t\t";
        //         $this->info('DONE');
        //     }
        // }

        // Migrating Consumer
        DB::setTablePrefix('');

        $result = DB::table('tbl_loyalty_consumer')->get();
        $this->info('Moving '.count($result).' consumers to the new tables');

        DB::setTablePrefix($oldPrefix);

        $emailCounter = 1;

        foreach ($result as $item) {
            if ($item->email === '') {
                $item->email = 'consumer' . $emailCounter . '@varaa.com';
                $emailCounter += 1;
            }

            $consumer = ConsumerModel::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                        ->where('consumers.email', '=', $item->email)->get();

            if(!$consumer->toArray()) {
                $core = new Core;
                $consumer = new ConsumerModel;
                $core->unguard();
                $consumer->unguard();
                $core->fill([
                    'id'            => $item->loyalty_consumer,
                    'first_name'    => $item->first_name,
                    'last_name'     => $item->last_name,
                    'email'         => $item->email,
                    'phone'         => $item->phone,
                    'address'       => $item->address1,
                    'postcode'      => '',
                    'city'          => $item->city,
                    'country'       => '',
                    'created_at'    => $item->created_time,
                    'updated_at'    => $item->updated_time,
                ]);

                $consumer->fill([
                    'id'            => $item->loyalty_consumer,
                    'consumer_id'   => $item->loyalty_consumer,
                    'total_points'  => $item->current_score,
                    'total_stamps'  => '',
                    'created_at'    => $item->created_time,
                    'updated_at'    => $item->updated_time,
                ]);

                $core->reguard();
                $consumer->reguard();

                if ($core->save() && $consumer->save()) {
                    echo "\t{$item->email} new\t\t";
                    $this->info('ADDED TO CORE CONSUMER');
                }
            } else {
                echo "\t{$item->email} duplicated\t\t";
                $this->info('SKIPPED');
            }

            DB::table('consumer_user')->insert([
                'consumer_id'   => $item->loyalty_consumer,
                'user_id'       => $item->owner,
                'is_visible'    => 1,
            ]);

            $this->info('DONE UPDATING USER-CONSUMER RELATIONSHIP');
        }

        // Migrating Transaction
        $totalStamps = [];
        DB::setTablePrefix('');
        $result = DB::table('tbl_loyalty_consumer_point')->get();
        $this->info('Making transactions table');
        DB::setTablePrefix($oldPrefix);

        foreach ($result as $item) {
            $voucher = VoucherModel::find($item->loyalty_point);

            if ($voucher) {
                // $transaction = new TransactionModel;
                // $transaction->user_id = $voucher->user_id;
                // $transaction->consumer_id = $item->loyalty_consumer;
                // $transaction->voucher_id = $item->loyalty_point;
                // $transaction->point = $voucher->required * -1;
                // $transaction->save();

                if (array_key_exists($voucher->id, $totalUsageOfVoucher)) {
                    $totalUsageOfVoucher[$voucher->id] += 1;
                } else {
                    $totalUsageOfVoucher[$voucher->id] = 1;
                }
            }
        }

        DB::setTablePrefix('');
        $result = DB::table('tbl_loyalty_consumer_stamp')->get();
        DB::setTablePrefix($oldPrefix);

        foreach ($result as $item) {
            $offer = OfferModel::find($item->loyalty_stamp);

            if ($offer) {
                // $transaction = new TransactionModel;
                // $transaction->user_id = $offer->user_id;
                // $transaction->consumer_id = $item->loyalty_consumer;
                // $transaction->offer_id = $item->loyalty_stamp;

                // if ($item->cnt_free === 0) {
                //     $transaction->stamp = 1;
                //     $transaction->free_service = 0;
                // } else {
                //     $transaction->stamp = $offer->required * -1;
                //     $transaction->free_service = 1;
                // }

                // $transaction->save();

                if (array_key_exists($offer->id, $totalUsageOfOffer)) {
                    $totalUsageOfOffer[$offer->id] += 1;
                } else {
                    $totalUsageOfOffer[$offer->id] = 1;
                }

                $totalStamps[$item->loyalty_consumer][$offer->id] = [$item->cnt_used, $item->cnt_free];
            }
        }

        // $this->info('Update total used time of offers');
        // foreach ($totalUsageOfOffer as $key => $value) {
        //     $offer = OfferModel::find($key);

        //     if ($offer) {
        //         $offer->total_used = $value;
        //         if ($offer->save()) {
        //             echo "\t{$offer->name} used {$value} times\t\t";
        //             $this->info('DONE');
        //         }
        //     }
        // }

        // $this->info('Update total used time of vouchers');
        // foreach ($totalUsageOfVoucher as $key => $value) {
        //     $voucher = VoucherModel::find($key);

        //     if ($voucher) {
        //         $voucher->total_used = $value;
        //         if ($voucher->save()) {
        //             echo "\t{$voucher->name} used {$value} times\t\t";
        //             $this->info('DONE');
        //         }
        //     }
        // }

        $this->info('Update total stamps to consumers');
        foreach ($totalStamps as $key => $value) {
            $consumerTotalStamps = json_encode($totalStamps[$key]);

            $consumer = ConsumerModel::find($key);

            if ($consumer) {
                $consumer->total_stamps = $consumerTotalStamps;
                $consumer->save();
            }
        }
        $this->info('DONE');
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
