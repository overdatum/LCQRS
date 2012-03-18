<?php

use LCQRS\Bus;

Bus::register('lcqrs.event: App\\Events\\RolesAssignedToAccount', function($message) {
	$event = unserialize($message);
});

Bus::register('lcqrs.event: App\\Events\\RolesUnassignedFromAccount', function($message) {
	$event = unserialize($message);
});