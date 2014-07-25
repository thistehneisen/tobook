<?php namespace Cmd\Migrate;

use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

abstract class Base {
	protected $output;
	protected $db;
	protected $currentUser;

	public function __construct(OutputInterface $output, Connection $db)
	{
		$this->output = $output;
		$this->db     = $db;
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
}
