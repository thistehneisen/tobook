<?php namespace Cmd\Migrate;

use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Common\Inflector\Inflector;

abstract class Base {
	protected $output;
	protected $db;
	protected $currentUser;
	protected $schemaManager;
	protected $originalUsername;
	protected $username;
	protected $autoIncrementFields = [];
	protected $tablePrefix = '';
	protected $tablePattern = null;
	protected $options;

	/**
	 * Contain map from old IDs to new IDs
	 *
	 * @var array
	 */
	protected $map = [];

	public function __construct(
		OutputInterface $output,
		Connection $db,
		array $options = array())
	{
		$this->output        = $output;
		$this->db            = $db;
		$this->schemaManager = $this->db->getSchemaManager();

		$this->options = $options;
	}

	abstract public function run();

	public function text($text, $newLine = true)
	{
		$method = ($newLine) ? 'writeln' : 'write';
		$this->output->$method($text);
		return $this;
	}

	public function info($text, $newLine = true)
	{
		$this->text('<info>'.$text.'</info>', $newLine);
		return $this;
	}

	public function comment($text, $newLine = true)
	{
		$this->text('<comment>'.$text.'</comment>', $newLine);
		return $this;		
	}

	public function question($text, $newLine = true)
	{
		$this->text('<question>'.$text.'</question>', $newLine);
		return $this;		
	}

	public function error($text, $newLine = true)
	{
		$this->text('<error>'.$text.'</error>', $newLine);
		return $this;		
	}

	public function getCurrentUser()
	{
		if ($this->currentUser === false
		 || $this->currentUser['vuser_login'] !== $this->originalUsername) {
			// Get user information from tbl_user_mast
			$stm = $this->queryBuilder()
				->select('*')
				->from('tbl_user_mast', 'u')
				->where('u.vuser_login = ?')
				->setParameter(0, $this->originalUsername)
				->execute();

			$user = $stm->fetch();
			if ($user === false) {
				$this->error("Cannot find information of $this->originalUsername. Retry later!");
			}

			$this->currentUser = $user;
		}

		return $this->currentUser;
	}

	/**
	 * In database, there are usernames having dash but their database prefix
	 * is without dash. So this method will remove dash in usernames.
	 * 
	 * @param  string $username
	 * 
	 * @return string
	 */
	protected function processUsername($username)
	{
		return str_replace('-', '', $username);
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

		// Prefer usernames over IDs
		if (isset($this->options['usernames']) 
			&& !empty($this->options['usernames'])) {
			return array_map('trim', explode(',', $this->options['usernames']));
		}

		$query = $this->queryBuilder()
			->select('vuser_login')
			->from('tbl_user_mast', 'u');

		if (isset($this->options['ids']) && !empty($this->options['ids'])) {
			$ids = array_map('trim', explode(',', $this->options['ids']));
			$query = $query->where('nuser_id IN ('.implode(',', $ids).')');
		}

		// Get all users
		$stm = $query->execute();

		while ($row = $stm->fetch()) {
			$username = $row['vuser_login'];
			$table = $username.$this->tablePattern;

			$result = $this->db->query("SHOW TABLES LIKE '$table'");
			if ($result->fetch()) {
				$usernames[$username] = true;
			}
		}

		return array_keys($usernames);
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
		$this->text("Migrating `$table`...", false);

		$user = $this->getCurrentUser();
		if ($user === false) {
			$this->error('ERROR: Cannot get data of current user');
			return;
		}

		foreach ($relationships as $tbl) {
			if (!is_array($tbl) && empty($this->map[$tbl])) {
				$this->error("No `$tbl` map.");
			}
		}

		$map = [];
		$stm = $query->execute();

		$rowCount = $stm->rowCount();
		$method = ($rowCount) === 0 ? 'question' : 'comment';
		$this->$method("$rowCount rows.", false);

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
                'left',
				'group',
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
				// deleted. So keep old values.
				// Some fields, `subcategories_id` for example, have default
				// value of 0, so we need to check it before mapping
				if ((int) $id > 0 && array_key_exists($id, $this->map[$target])) {
					$row[$field] = $this->map[$target][$id];
				} elseif ((int) $id > 0 && !array_key_exists($id, $this->map[$target])) {
					// Not a zero, NULL, etc. value but cannot find a map -> skip it
					$skip = true;
				}
			}

			if ($skip) { continue; }

			$this->db->insert($this->tablePrefix.$table, $row);

			if ($mapped) {
				$map[$oldId] = $this->db->lastInsertId();
			}
		}

		$this->map[$table] = $map;
		$this->output->writeln('<fg=green;option=bold>SUCCEEDED</fg=green;option=bold>');
		return $map;
	}

	
}
