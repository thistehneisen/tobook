<?php namespace App\Appointment\Commands;
use Illuminate\Console\Command;
use DB, Carbon\Carbon, Closure;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FixBookingsNotesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:as-fix-booking-notes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate notes from as_bookings to varaa_as_bookings';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Update bookings\' notes');
        DB::setTablePrefix('');

        $all = DB::table('as_bookings')->whereNotNull('c_notes')->get();
        foreach ($all as $item) {
            DB::table('varaa_as_bookings')
                ->where('uuid', $item->uuid)
                ->update(['notes' => $item->c_notes]);

            echo '.';
        }

        $this->info('Done');
    }
}
