<?php namespace LCQRS\Message\Drivers;

use Laravel\Event;
use Closure;

class Directly {

	/**
	 * Publish a message to a channel
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function publish($channel, $arguments)
	{
		Event::fire($channel, $arguments);
	}

	/**
	 * Add subsciption for channel
	 *
	 * @param  string   $channel
	 * @param  closure  $callback
	 * @return void
	 */
	public function subscribe($channel, Closure $callback)
	{
		Event::listen($channel, $callback);
	}

}