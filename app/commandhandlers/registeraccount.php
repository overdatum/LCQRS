<?php namespace App\CommandHandlers;

use LCQRS\Bus;
use App\Events\AccountRegistered;
use App\AggregateRoots\Account;

class RegisterAccount {

	public function __construct($command)
	{
		$account = new Account($command->attributes['uuid']);
		$account->register($command->attributes);
		Bus::publish(new AccountRegistered($command->attributes));
	}

}