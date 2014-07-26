<?php namespace Cmd\Migrate;

use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Common\Inflector\Inflector;

abstract class Base {
	protected $output;
	protected $db;
	protected $currentUser;
	protected $schemaManager;
	protected $username;
	protected $autoIncrementFields = [];
	protected $tablePrefix = '';
	protected $tablePattern = null;

	/**
	 * Contain map from old IDs to new IDs
	 *
	 * @var array
	 */
	protected $map = [];

	public function __construct(OutputInterface $output, Connection $db)
	{
		$this->output        = $output;
		$this->db            = $db;
		$this->schemaManager = $this->db->getSchemaManager();
	}

	abstract public function run();

	public function text($text)
	{
		$this->output->writeln($text);
		return $this;
	}

	public function info($text)
	{
		$this->output->writeln('<info>'.$text.'</info>');
		return $this;
	}

	public function comment($text)
	{
		$this->output->writeln('<comment>'.$text.'</comment>');
		return $this;		
	}

	public function question($text)
	{
		$this->output->writeln('<question>'.$text.'</question>');
		return $this;		
	}

	public function error($text)
	{
		$this->output->writeln('<error>'.$text.'</error>');
		return $this;		
	}

	public function getCurrentUser()
	{
		if ($this->currentUser === false
		 || $this->currentUser['vuser_login'] !== $this->username) {
			// Get user information from tbl_user_mast
			$stm = $this->queryBuilder()
				->select('*')
				->from('tbl_user_mast', 'u')
				->where('u.vuser_login = ?')
				->setParameter(0, $this->username)
				->execute();

			$user = $stm->fetch();
			if ($user === false) {
				$this->error("Cannot find information of $this->username. Retry later!");
			}

			$this->currentUser = $user;
		}

		return $this->currentUser;
	}

	/**
	 * Return a new instance of QueryBuilder
	 *
	 * @return \Doctrine\DBAL\Query\QueryBuilder
	 */
	protected function queryBuilder()
	{
		return $this->db->createQueryBuilder();
	}

	protected function getUsernames()
	{
		$this->text('Getting a list of users to be proceeded...');
		$usernames = [];

		// Get the list of tables
		$tables = $this->schemaManager->listTables();
		foreach ($tables as $table)
		{
			preg_match($this->tablePattern, $table->getName(), $matches);
			if (isset($matches[1])) {
				$usernames[] = $matches[1];
			}
		}
		$this->text('Found '.count($usernames).' users');

		return $usernames;
	}

	/**
	 * Shorter version of `migrate()`, in case you don't need to specify query
	 *
	 * @param  string $table         
	 * @param  array $relationships 
	 *
	 * @return array
	 */
	protected function migrateTable($table, $relationships = [])
	{
		$query = $this->queryBuilder()
			->select('*')
			->from($this->username.'_'.$this->tablePrefix.$table, 't');

		return $this->migrate($table, $query, $relationships);
	}

	/**
	 * Migrate data of a table, store a map of old IDs to new IDs
	 *
	 * @param  string  $table         Table name, should be in plural without
	 *                                `sma` prefix
	 * @param  Stament $query         Statement to get data from that table
	 * @param  array   $relationships Specify the relationships to other tables.
	 * Example:
	 * <code>
	 * ['user_id'  => 'users',
	 * 	'group_id' => 'groups']
	 * </code>
	 *
	 * @return array The map of old IDs to new IDs
	 */
	protected function migrate($table, $query, $relationships = [])
	{
		$this->text('Migrating `'.$table.'`...');

		$user = $this->getCurrentUser();
		if ($user === false) {
			$this->error('ERROR: Cannot get data of current user');
			return;
		}

		foreach ($relationships as $tbl) {
			if (!is_array($tbl) && empty($this->map[$tbl])) {
				$this->error("ERROR: Empty map of `$tbl`.");
				return;
			}
		}

		$map = [];
		$stm = $query->execute();

		if ($stm->rowCount() === 0) {
			$this->comment("Table `$table` doesn't have data.");
		}

		while ($row = $stm->fetch()) {
			$mapped = isset($row['id']);
			if ($mapped) {
				$oldId = $row['id'];
			}

			// Some auto increment fields need to be removed
			foreach ($this->autoIncrementFields as $idField) {
				if (isset($row[$idField])) {
					unset($row[$idField]);
				}
			}

			$row['owner_id'] = $user['nuser_id'];

			// Quick fix for reserved keywords
			$keywords = [
				'sql',
				'order',
				'key',
				'before',
				'after',
			];
			foreach ($keywords as $key) {
				if (array_key_exists($key, $row)) {
					$row["`$key`"] = $row[$key];
					unset($row[$key]);
				}
			}

			// Map relationships
			$skip = false;
			foreach ($relationships as $field => $tbl) {
				$id = $row[$field];

				$target = $tbl;
				if (is_array($tbl) && !empty($tbl)) {
					// The first item is the field containing the target table
					// Because the type in plural forms, so we need to change
					// it first
					$target = Inflector::pluralize($row[$tbl[0]]);
				}

				// Cannot find ID in the map, maybe target relationship was
				// deleted. So no need to keep this record.
				if (!isset($this->map[$target][$id])) {
					$skip = true;
					break;
				}

				$row[$field] = $this->map[$target][$id];
			}

			if ($skip === true) {
				continue;
			}

			$this->db->insert($this->tablePrefix.$table, $row);

			if ($mapped) {
				$map[$oldId] = $this->db->lastInsertId();
			}
		}

		$this->map[$table] = $map;
		return $map;
	}

	
}
