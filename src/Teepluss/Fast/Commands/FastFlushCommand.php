<?php namespace Teepluss\Fast\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FastFlushCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fast:flush';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Flush all cache from fast.';

	/**
	 * Fast.
	 *
	 * @var \Teepluss\Fast\Fast
	 */
	protected $fast;

	/**
	 * Create a new command instance.
	 *
	 * @param \Teepluss\Fast\Fast $fast
	 */
	public function __construct($fast)
	{
		parent::__construct();

		$this->fast = $fast;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->fast->flush();

		$this->info('Successfully flushed');
	}

}
