<?php namespace App\CommandHandlers;

use LCQRS\Repository;
use LCQRS\Bus;

use App\Events\AccountUpdated;
use App\AggregateRoots\Account;

class UpdateAccount {
	
	public function __construct($command)
	{
		$account = Repository::load($command->attributes['uuid'], new Account);
		$account->update($command->attributes);

		Repository::save($account);
		Bus::publish(new AccountUpdated($command->attributes));
	}

}