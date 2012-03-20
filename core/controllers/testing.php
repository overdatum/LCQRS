<?php

use LCQRS\Repository;
use LCQRS\Bus;
use LCQRS\Libraries\UUID;

use App\AggregateRoots\Account;
use App\Entities\Role;

use App\Commands\CreateRole;
use App\Commands\RegisterAccount;
use App\Commands\UpdateAccount;
use App\Commands\AssignRolesToAccount;
use App\Commands\UnassignRolesFromAccount;

class LCQRS_Testing_Controller extends Controller {
	
	public function action_index()
	{
		$account_uuid = UUID::generate();
		$admin_role_uuid = UUID::generate();
		$agent_role_uuid = UUID::generate();

		Bus::send(new CreateRole($admin_role_uuid, array('key' => 'admin')));
		Bus::send(new CreateRole($agent_role_uuid, array('key' => 'agent')));

		Bus::send(new RegisterAccount($account_uuid, array('first_name' => 'Koen', 'last_name' => 'Smeets')));
		Bus::send(new UpdateAccount($account_uuid, array('last_name' => 'Schmeets')));
		Bus::send(new AssignRolesToAccount($account_uuid, array('role_uuids' => array($admin_role_uuid, $agent_role_uuid))));
		Bus::send(new UnassignRolesFromAccount($account_uuid, array('role_uuids' => array($agent_role_uuid))));
	
		var_dump(Repository::load($account_uuid, new Account));
	}

}