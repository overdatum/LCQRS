<?php namespace LCQRS;

use LCQRS\Message;

class Bus {
	
	public static function register($channel, $callback)
	{
		Message::subscribe($channel, $callback);
	}

	public static function send($command)
	{
		$identifier = get_class($command);
		Message::publish('lcqrs.command', array($identifier, serialize($command)));
	}

	public static function publish($event)
	{
		$identifier = get_class($event);
		Message::publish("lcqrs.event: {$identifier}", array(serialize($event)));
	}

}