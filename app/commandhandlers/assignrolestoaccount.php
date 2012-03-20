<?php namespace App\CommandHandlers;

use LCQRS\Bus;
use App\Events\RolesAssignedToAccount;
use App\AggregateRoots\Account;

class AssignRolesToAccount {
	
	public function __construct($command)
	{
		$account = new Account($command->attributes['uuid']);
		$account->assign_roles($command->attributes);
		Bus::publish(new RolesAssignedToAccount($command->attributes));
	}

}