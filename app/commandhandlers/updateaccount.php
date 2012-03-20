<?php namespace App\CommandHandlers;

use LCQRS\Bus;
use App\Events\AccountUpdated;
use App\AggregateRoots\Account;

class UpdateAccount {
	
	public function __construct($command)
	{
		$account = new Account($command->attributes['uuid']);
		$account->update($command->attributes);
		Bus::publish(new AccountUpdated($command->attributes));
	}

}