<?php namespace App\Commands;

use DB;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FixSchemaCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:fix-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix owner_id and FK of modules to be referred to varaa_users';

    /**
     * Doctrine schema manager
     *
     * @var Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected $manager;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->manager = DB::getDoctrineSchemaManager();
        $platform = $this->manager->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        $this->getAllTables();
    }

    protected function getAllTables()
    {
        $tables = $this->manager->listTables();
        foreach ($tables as $table) {
            if (starts_with($table->getName(), 'varaa_')
                || $table->hasColumn('owner_id') === false) {
                continue;
            }

            $this->processTable($table);
        }
    }

    public function processTable(Table $table)
    {
        $this->comment('Now fixing `'.$table->getName().'`');
        $sql = '';
        $fks = $this->manager->listTableForeignKeys($table->getName());
        foreach ($fks as $fk) {
            if ($fk->getForeignTableName() === 'tbl_user_mast') {
                // Drop this FK
                echo "\tDropping existing FK\n";
                DB::statement(sprintf(
                    "ALTER TABLE `%s` DROP FOREIGN KEY `%s`;\n",
                    $table->getName(),
                    $fk->getName()
                ));
                break;
            }
        }

        // Delete users that no longer exist in varaa_users
        echo "\tDeleting obsolete users\n";
        $format = <<< SQL
DELETE FROM %s
WHERE  owner_id IN (SELECT owner_id 
                    FROM   (SELECT DISTINCT owner_id 
                            FROM   %s a 
                            WHERE  NOT EXISTS (SELECT * 
                                               FROM   varaa_users 
                                               WHERE  id = a.owner_id)) AS C);
SQL;
        $sql = sprintf($format, $table->getName(),$table->getName());
        DB::delete($sql);

        echo "\tChanging data type\n";
        $sql = sprintf(
            'ALTER TABLE `%s` CHANGE `owner_id` `owner_id` int(10) unsigned NOT NULL;',
            $table->getName()
        );
        DB::statement($sql);

        echo "\tNew FK was born\n";
        $sql = sprintf(
            'ALTER TABLE `%s` ADD FOREIGN KEY (`owner_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE;',
            $table->getName()
        );
        DB::statement($sql);

        $this->info("\tDONE");
    }
}
