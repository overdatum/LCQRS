<?php namespace App\CommandHandlers;

use LCQRS\Repository;
use LCQRS\Bus;

use App\Events\RolesUnassignedFromAccount;
use App\AggregateRoots\Account;

class UnassignRolesFromAccount {
	
	public function __construct($command)
	{
		$account = Repository::load($command->attributes['uuid'], new Account);
		$account->unassign_roles($command->attributes);

		Repository::save($account);
		Bus::publish(new RolesUnassignedFromAccount($command->attributes));
	}

}