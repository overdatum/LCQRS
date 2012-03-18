<?php

use LCQRS\Bus;

Bus::register('lcqrs.event: App\\Events\\AccountRegistered', function($message) {
	$event = unserialize($message);
});

Bus::register('lcqrs.event: App\\Events\\AccountUpdated', function($message) {
	$event = unserialize($message);
});