<?php namespace App\AggregateRoots;

use LCQRS\AggregateRoot;
use App\Events\Campaign\CampaignCreated;
use App\Events\Campaign\CampaignUpdated;

class Campaign extends AggregateRoot {

	public function __construct($attributes)
	{
		$this->create($attributes);
	}

	public function create_draft($attributes)
	{
		$this->apply(new CampaignDraftCreated($attributes));
	}

	public function update($attributes)
	{
		$this->apply(new CampaignUpdated($this->uuid, $attributes));
	}

	protected function on_created_event(CampaignCreated $event)
	{
		$this->attributes = $event->attributes;
	}

	protected function on_updated_event(CampaignUpdated $event)
	{
		$this->attributes = array_merge($this->attributes, $event->attributes);
	}

}