<?php

namespace Sweetkit\Foundation\Event;

use Sweetkit\Foundation\Event\EventManager;

trait EventAwareTrait
{
	protected $event;

	function setEventManager(EventManager $event) : void
	{
		$this->event = $event;
	}
}