<?php namespace LCQRS\Model;

use Exception;
use Laravel\IoC;
use Laravel\Str;
use Laravel\Event;

class Entity {

	public $_attributes = array();

	public $_changes = array();

	/**
	 * Magic Get method, allowing you to have very simple DTO's
	 */
	public function __get($key)
	{
		if($key == 'attributes')
			return $this->_attributes;
		
		return $this->_attributes[$key];
	}

	/**
	 * Magic Set method, allowing you to have very simple DTO's
	 */
	public function __set($key, $value)
	{
		if($key == 'attributes')
			$this->_attributes = $value;
		else
			$this->_attributes[$key] = $value;
	}

	public function take_changes()
	{
		$changes = $this->_changes;
		unset($this->_changes);

		return $changes;
	}

	protected function apply($event, $is_new = true)
	{
		if($is_new)
		{
			$this->_changes[] = $event;
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

		return 'apply_'.$underscored_event_name;
	}

	protected function get_aggregate_name()
	{
		$segments = explode('\\', get_class($this));
					
		return array_pop($segments);
	}

	public function load_from_history($events)
	{
		foreach($events as $event)
		{
			$this->apply($event, false);
		}
	}

}