<?php namespace App\Core\Commands;

use DB;
use Illuminate\Console\Command;

class FixEscapedCharactersCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:fix-escaped-characters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix escaped characters in database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Fix businesses
        $table = 'businesses';
        $result = DB::table($table)
            ->select('id', 'name', 'description', 'address', 'city', 'meta_title','meta_description','meta_keywords')
            ->get();
        $this->fix($result, $table);

        $this->output->writeln('');
        $this->info('Done. Please delete all ES indices if required.');
    }

    protected function fix($result, $table)
    {
        foreach ($result as $item) {
            $attributes = [];
            foreach ((array) $item as $key => $value) {
                $attributes[$key] = html_entity_decode($value);
            }

            unset($attributes['id']);

            DB::table($table)->where('id', $item->id)
                ->update($attributes);
            $this->output->write('.');
        }
    }
}
