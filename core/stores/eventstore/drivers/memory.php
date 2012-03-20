<?php namespace LCQRS\Stores\EventStore\Drivers;

use Laravel\Database as DB;

class Memory {

	public $memory = array();

	/**
	 * Get all events for an Entity by it's UUID
	 *
	 * @param $uuid An Entity's UUID
	 *
	 * @return array
	 */
	public function get_all_events($uuid)
	{
		return $this->get_events_from_version($uuid, 0);
	}

	/**
	 * Get all events for an Entity by it's UUID from a given version
	 *
	 * @param $uuid An Entity's UUID
	 * @param $version The first event version
	 *
	 * @return array
	 */
	public function get_events_from_version($uuid, $version)
	{
		$events = array();
		
		if(array_key_exists($uuid, $this->memory))
		{
			$rows = array_slice($this->memory[$uuid], $version);
			foreach ($rows as $event) {
				$events[] = unserialize($event);
			}
		}

		return $events;
	}

	/**
	 * Get last version for an Entity by it's UUID
	 *
	 * @param $uuid An Entity's UUID
	 *
	 * @return array
	 */
	public function get_last_version($uuid)
	{
		if(array_key_exists($uuid, $this->memory)) return count($this->memory[$uuid]);
		return 0;
	}

	/**
	 * Add an Event to the EventStore for an Entity
	 *
	 * @param UUID $uuid An Entity's UUID
	 * @param Event $event An Event
	 *
	 * @return void
	 */
	public function save_events($uuid, $class_name, $events)
	{
		$version = $this->get_last_version($uuid) + 1;
		foreach ($events as $event)
		{
			$this->memory[$uuid][$version] = serialize($event);
			$version++;
		}
	}

}