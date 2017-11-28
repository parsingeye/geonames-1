<?php namespace Ipalaus\Geonames\Commands;

use RuntimeException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'geonames:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish the config and migrations files.';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$force = $this->input->getOption('force');

		$path = $this->laravel['path.base'].'/app/config/packages/ipalaus/geonames/config.php';

		// prevents config overwrites
		if ($this->configExists($path) and ! $force)
		{
			throw new RuntimeException('Config file exists. Use --force if you want to ignore this error and overwrite it.');
		}

		$this->call('config:publish', array('package' => 'ipalaus/geonames'));
		$this->call('migrate:publish', array('package' => 'ipalaus/geonames'));
	}

	/**
	 * Check if a config file exists.
	 *
	 * @param  string  $path
	 * @return bool
	 */
	protected function configExists($path)
	{
		return file_exists($path);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('force', 'f', InputOption::VALUE_NONE, 'Forces overwriting the config file and publishing the migrations.'),
		);
	}

}
