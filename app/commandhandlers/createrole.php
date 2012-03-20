<?php namespace App\CommandHandlers;

use LCQRS\Repository;
use LCQRS\Bus;

use App\Events\RoleCreated;
use App\Entities\Role;

class CreateRole {
	
	public function __construct($command)
	{
		$role = Repository::load($command->attributes['uuid'],  new Role);
		$role->create($command->attributes);

		Repository::save($role);
		Bus::publish(new RoleCreated($command->attributes));
	}

}