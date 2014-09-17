<?php namespace App\FlashDeal\Commands;

use Illuminate\Console\Command;
use DB, Carbon\Carbon, Closure;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FixUserIdCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:fd-fix-user-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate missing user_id from fd_services to other FD tables';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Update coupons and flash deals');

        $services = DB::table('fd_services')->get();
        foreach ($services as $service) {
            DB::table('fd_coupons')
                ->where('service_id', $service->id)
                ->update(['user_id' => $service->user_id]);

            DB::table('fd_flash_deals')
                ->where('service_id', $service->id)
                ->update(['user_id' => $service->user_id]);

            echo '.';
        }

        $this->info('Update dates');
        $deals = DB::table('fd_flash_deals')->get();
        foreach ($deals as $deal) {
            DB::table('fd_flash_deal_dates')
                ->where('flash_deal_id', $deal->id)
                ->update(['user_id' => $deal->user_id]);

            echo '.';
        }

        $this->info('Done');
    }
}
