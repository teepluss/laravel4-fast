<?php namespace Teepluss\Fast\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FastForgetCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fast:forget';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear cache from fast.';

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
		$cacheKey = $this->argument('key');

		// Key cache is not exists.
		if ( ! $this->fast->has($cacheKey))
		{
			return $this->error('Cache key not found');
		}

		// For get cache.
		$this->fast->forget($cacheKey);

		$this->info('Successfully removed');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('key', InputArgument::REQUIRED, 'Cache key.'),
		);
	}

}
