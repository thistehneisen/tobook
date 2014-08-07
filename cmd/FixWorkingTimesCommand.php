<?php namespace Cmd;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class FixWorkingTimesCommand extends Command
{
    private $db;
    private $employeeMap = [];
    private $doItLater = [];

    protected function configure()
    {
        $this->setName('fix-wt')
        ->setDescription('Fix as_working_times');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = require realpath(__DIR__.'/../config.php');
        $this->db = DriverManager::getConnection([
            'dbname'   => $config['db']['name'],
            'user'     => $config['db']['user'],
            'password' => $config['db']['password'],
            'host'     => $config['db']['host'],
            'driver'   => 'pdo_mysql',
        ], new Configuration);

        // Fix unknow `enum` type
        // http://wildlyinaccurate.com/doctrine-2-resolving-unknown-database-type-enum-requested
        $platform = $this->db->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        // Get a list of owner_id
        $output->writeln('<info>Getting the list of users...</info>');
        $stm = $this->db->createQueryBuilder()
            ->select('vuser_login, nuser_id')
            ->from('tbl_user_mast', 'u')
            ->execute();

        $users = [];
        while ($row = $stm->fetch()) {
            if ($this->hasTable($row['vuser_login'])) {
                $id = $row['nuser_id'];
                $username = $row['vuser_login'];
                $users[$id] = $username;
            }
        }

        foreach ($users as $id => $username) {
            $output->writeln("Now processing <info>#{$id} {$username}</info>");
            $this->process($id, $username);
        }

        foreach ($this->doItLater as $item) {
            $output->writeln("Remaining...");
            list ($ownerId, $username, $row) = $item;
            $this->updateData($ownerId, $username, $row);
        }

    }

    /**
     * Check if this user has AS installed
     *
     * @param  string  $username 
     *
     * @return boolean           
     */
    protected function hasTable($username)
    {
        $table = $username.'_hey_appscheduler_working_times';
        $result = $this->db->query("SHOW TABLES LIKE '$table'");
        if ($result->fetch()) {
            return true;
        }

        return false;
    }

    protected function process($ownerId, $username)
    {
        $stm = $this->db->createQueryBuilder()
            ->select('*')
            ->from($username.'_hey_appscheduler_working_times', 't')
            ->where('type = ?')
            ->orderBy('foreign_id', 'desc')
            ->setParameter(0, 'employee')
            ->execute();

        while ($row = $stm->fetch()) {
            $this->updateData($ownerId, $username, $row);
        }
    }

    protected function updateData($ownerId, $username, $row)
    {
        $original = $row;
        $employeeId = $this->mapEmployeeId(
            $row['foreign_id'],
            $username,
            $ownerId);
        if (!$employeeId) {
            return;
        }

        // Update foreign_id
        $oldForeignId = $row['foreign_id'];
        $row['foreign_id'] = $employeeId;

        // Remove ID
        unset($row['id']);

        // Update data
        $query = $this->db->createQueryBuilder()
            ->update('as_working_times', 't')
            ->where('owner_id = ?')
            ->andWhere('foreign_id = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $ownerId)
            ->setParameter(1, $oldForeignId)
            ->setParameter(2, 'employee');

        foreach ($row as $field => $value) {
            $query->set($field, "'{$value}'");
        }

        try {
            $query->execute();
        } catch (\Exception $ex) {
            echo "\t^ Do it later\n";
            $this->doItLater[] = [
                $ownerId,
                $username,
                $original
            ];
        }
    }

    protected function mapEmployeeId($fromId, $username, $ownerId)
    {
        if (!isset($this->employeeMap[$username][$fromId])) {
            $sql = <<< SQL
SELECT id, email FROM `as_employees` WHERE `email` = (
    SELECT email FROM `{$username}_hey_appscheduler_employees`
    WHERE id = ?) AND `owner_id` = ?
SQL;
            $stm = $this->db->prepare($sql);
            $stm->bindValue(1, $fromId);
            $stm->bindValue(2, $ownerId);
            $stm->execute();
            
            $result = $stm->fetch();
            if (!$result) {
                return null;
            }

            echo "\tOld ID {$fromId} => {$result['email']} => {$result['id']}\n";
            $this->employeeMap[$username][$fromId] = (int) $result['id'];
        }

        return $this->employeeMap[$username][$fromId];
    }

}
