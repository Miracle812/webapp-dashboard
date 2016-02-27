<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class cronTasks extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'crontasks:start';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'php artisan crontasks:start [daily|hourly|(minutes INT)]';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        switch($peroid = $this->argument('period')){
            case 'daily' : \Services\Cron::daily(); break;
            case 'hourly': \Services\Cron::hourly(); break;
            case 'minutes': \Services\Cron::minutes($this->argument('minutes')?:5); break; //def 5 minutes
            default: \Services\Cron::daily(); break;
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('period', InputArgument::OPTIONAL, 'A period argument.'),
			array('minutes', InputArgument::OPTIONAL, 'A minutes value.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('period', null, InputOption::VALUE_OPTIONAL, 'An example period.', null),
		);
	}
}