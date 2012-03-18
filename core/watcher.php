<?php namespace LCQRS;

class Watcher {
}

Watcher::for('event: AccountRegistered', array('as' => 'registered'))->and('command: RemoveAccount', array('as' => 'removed'))->max('15 minutes')->then(function($messages) {
	CommandMessage::send(new AskReasonForFastRemovalOfAccountViaEmail($messages['registered'], $messages['removed']));
});