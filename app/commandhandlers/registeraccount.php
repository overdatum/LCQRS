<?php namespace App\CommandHandlers;

use LCQRS\Repository;
use LCQRS\Bus;

use App\Events\AccountRegistered;
use App\AggregateRoots\Account;

class RegisterAccount {

	public function __construct($command)
	{
		$account = Repository::load($command->attributes['uuid'], new Account);
		$account->register($command->attributes);

		Repository::save($account);
		Bus::publish(new AccountRegistered($command->attributes));
	}

}