<?php namespace App\Core\Commands;

use DB;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Console\Command;
use App\Core\Models\Business;

class FixDuplicateBusinessesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:fix-duplicate-businesses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix duplicate business info';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $sql = <<< SQL
SELECT  id
FROM    varaa_businesses a
WHERE   EXISTS ( SELECT *
                 FROM   varaa_businesses b
                 WHERE  a.user_id = b.user_id
                        AND a.id > b.id );
SQL;
        $result = DB::select($sql);
        foreach ($result as $row) {
            DB::delete('DELETE FROM varaa_businesses WHERE id = ?', [$row->id]);
        }
        $this->info('Done');
    }
}
