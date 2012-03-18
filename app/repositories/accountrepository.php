<?php namespace App\Repositories;

use App\Entities\Account;

class AccountRepository {

	public function get_by_uuid($uuid)
	{
		return new Account($uuid);
	}

}