<?php namespace App\CommandHandlers;

use LCQRS\Repository;
use LCQRS\Bus;

use App\Events\RolesAssignedToAccount;
use App\AggregateRoots\Account;

class AssignRolesToAccount {
	
	public function __construct($command)
	{
		$account = Repository::load($command->attributes['uuid'], new Account);
		$account->assign_roles($command->attributes);
		
		Repository::save($account);
		Bus::publish(new RolesAssignedToAccount($command->attributes));
	}

}