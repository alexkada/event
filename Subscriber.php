<?php

namespace Sweetkit\Foundation\Event;

use Sweetkit\Foundation\Event\EventManager;

abstract class Subscriber
{
	abstract public function subscribe(EventManager $event);
}