<?php namespace LCQRS\EventStore\Drivers;

use Laravel\Database as DB;

class PDO {

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

		$rows = DB::table('events')->where_uuid($uuid)->where('version', '>=', $version)->order_by('id', 'ASC')->get(array('event'));
		foreach($rows as $row)
		{
			$events[] = unserialize($row->event);
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
		$version = DB::table('events')->where_uuid($uuid)->max('version');
		return is_null($version) ? 0 : $version;
	}

	/**
	 * Add an Event to the EventStore for an Entity
	 *
	 * @param UUID $uuid An Entity's UUID
	 * @param Event $event An Event
	 *
	 * @return void
	 */
	public function add($uuid, $event)
	{
		$version = $this->get_last_version($uuid);
		DB::table('events')->insert(array(
			'uuid' => $uuid,
			'event' => serialize($event),
			'version' => $version + 1
		));	
	}

}
