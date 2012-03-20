<?php namespace App\Entities;

use LCQRS\Model\Entity;

use App\Events\RoleCreated;

class Role extends Entity {

	public function create($attributes)
	{
		$this->apply(new RoleCreated($attributes));
	}



	protected function apply_role_created(RoleCreated $event)
	{
		$this->attributes = $event->attributes;
	}

}