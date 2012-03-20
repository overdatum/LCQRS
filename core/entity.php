<?php namespace LCQRS;

use Exception;
use Laravel\IoC;
use Laravel\Str;
use Laravel\Event;

class Entity {

	public $attributes = array();

	/**
	 * Magic Get method, allowing you to have very simple DTO's
	 */
	public function __get($key)
	{
		return $this->attributes[$key];
	}

	/**
	 * Magic Set method, allowing you to have very simple DTO's
	 */
	public function __set($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	public function __construct($uuid = null, $load_from_history = true)
	{
		if(is_null($uuid)) return $this;

		$this->uuid = $uuid;
		if($load_from_history)
		{
			$events = EventStore::get_all_events($this->uuid);
			$this->load_from_history($events);
		}
	}

	public function apply($event, $add = true)
	{
		if($add)
		{
			EventStore::add($this->uuid, $event);
		}
		
		$apply_method = $this->to_apply_method($event);
		if(method_exists(get_called_class(), $apply_method))
		{
			return $this->$apply_method($event);
		}
	}

	protected function to_apply_method($event)
	{
		$segments = explode('\\', get_class($event));
		$event_name = array_pop($segments);
		$underscored_event_name = uncamelcase($event_name);

		return 'on_'.$underscored_event_name;
	}

	protected function get_aggregate_name()
	{
		$segments = explode('\\', get_class($this));
					
		return array_pop($segments);
	}

	public function load_from_history($events)
	{
		var_dump($events);
		foreach($events as $event)
		{
			$this->apply($event, false);
		}
	}

}