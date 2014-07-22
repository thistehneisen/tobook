<?php namespace Cmd\Migrate;

use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

abstract class Base {
	protected $output;
	protected $db;

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
}
