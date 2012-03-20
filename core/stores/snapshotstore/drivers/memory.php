<?php namespace LCQRS\Stores\SnapshotStore\Drivers;

class Memory {

	public function get($uuid)
	{
		return array(array(), 1);
	}

}