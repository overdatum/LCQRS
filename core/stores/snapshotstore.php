<?php namespace LCQRS\Stores;

use Laravel\Config;
use Exception;

class SnapshotStore {
	
	/**
	 * All of the active EventStore drivers.
	 *
	 * @var array
	 */
	public static $drivers = array();

	/**
	 * Get the SnapshotStore driver instance.
	 *
	 * If no driver name is specified, the default will be returned.
	 *
	 * <code>
	 *		// Get the default message driver instance
	 *		$driver = SnapshotStore::driver();
	 *
	 *		// Get a specific message driver instance by name
	 *		$driver = SnapshotStore::driver('memory');
	 * </code>
	 *
	 * @param  string        $driver
	 * @return Stores\SnapshotStore\Drivers\Driver
	 */
	public static function driver($driver = null)
	{
		if (is_null($driver)) $driver = Config::get('lcqrs::eventstore.driver');

		if ( ! isset(static::$drivers[$driver]))
		{
			static::$drivers[$driver] = static::factory($driver);
		}

		return static::$drivers[$driver];
	}

	/**
	 * Create a new message driver instance.
	 *
	 * @param  string  $driver
	 * @return Stores\SnapshotStore\Drivers\Driver
	 */
	protected static function factory($driver)
	{
		if( ! $driver) $drive = Config::get('lcqrs::eventstore.driver');

		switch ($driver)
		{
			case 'memory':
				return new SnapshotStore\Drivers\Memory;

			case 'pdo':
				return new SnapshotStore\Drivers\PDO;

			default:
				throw new \Exception("SnapshotStore driver {$driver} is not supported.");
		}
	}

	/**
	 * Magic Method for calling the methods on the default SnapshotStore driver.
	 *
	 * <code>
	 *		// Get the latest snapshot for an Entity by it's UUID
	 *		SnapshotStore::get_by_uuid($uuid)
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}
	
}