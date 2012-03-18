<?php namespace App\CommandHandlers;

use LCQRS\Bus;
use App\Events\RoleCreated;
use App\Entities\Role;

class CreateRole {
	
	public function __construct($command)
	{
		$role = new Role;
		$role->create($command->attributes);
		Bus::publish(new RoleCreated($command->attributes));
	}

}