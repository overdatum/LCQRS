<?php namespace LCQRS;

use LCQRS\Stores\EventStore;
use LCQRS\Stores\SnapshotStore;

class Repository {
	
	public static function load($uuid, $entity)
	{
		list($snapshot, $version) = SnapshotStore::get($uuid);
		$events = array_merge($snapshot, EventStore::get_events_from_version($uuid, $version));
		$entity->load_from_history($events);

		return $entity;
	}

	public static function save($entity)
	{
		EventStore::save_events($entity->uuid, get_class($entity), $entity->take_changes());
	}

}