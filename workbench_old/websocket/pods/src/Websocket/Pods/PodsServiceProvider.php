<?php namespace Websocket\Pods;

use Illuminate\Support\ServiceProvider;
use Evenement\EventEmitter;


class PodsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->bind("pods.emitter", function()
		{
			return new EventEmitter();
		});

		$this->app->bind("pods.hubs_pusher", function()
		{
			return new HubsPusher(
				$this->app->make("pods.emitter")
			);
		});

		$this->app->bind("pods.device", function()
		{
			return new \Model\PodsDevices();
		});


		$this->app->bind("pods.command.serve", function()
		{
			return new Command\Serve(
				$this->app->make("pods.hubs_pusher")
			);
		});

		$this->commands("pods.command.serve");

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			"pods.hubs_pusher",
			"pods.command.serve",
			"pods.emitter",
			"pods.server"
		];
	}

}
