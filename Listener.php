<?php

namespace Sweetkit\Foundation\Event;

use Sweetkit\Foundation\Event\Event;
use Sweetkit\Foundation\Event\Exceptions\{NotFoundException, InvalidArgumentException, NotCancelableException};
class Listener
{
	protected $name;
	protected $handler;
	protected $response;
    protected $cancelable = true;
    protected $event;
    protected $attributes;

	public function __construct(Event $event, string $name,  $handler, bool $cancelable = true, array $attributes = [])
	{
		$this->setEvent($event);
        if (is_string($handler) === false AND
            is_callable($handler) === false AND
        	is_array($handler) === false) {
            throw new InvalidArgumentException("Not a valid handler type");
        }	
		$this->name = $name;
		$this->handler = $handler;
		$this->cancelable = $cancelable;
		$this->setAttributes($attributes);
	}

	public function getAttributes() : array
    {
        return $this->attributes;
    }
    public function setAttributes(array $attributes) : void
    {
        $this->attributes = $attributes;
    }
	public function setEvent(Event $event) : void
	{
		$this->event = $event;
	}

	public function getEvent() : Event
	{
		return $this->event;
	}

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param  bool $flag
     */
    public function stopPropagation() : void
    {
        $this->event->setPropagation($this->getName(), true);
    }

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped() : bool
    {
        return $this->event->getPropagation($this->getName());
    }

	public function getName() : string
	{
		return $this->name;
	}

	public function getHandler()
	{
		return $this->handler;
	}

	public function execute(array $arguments = []) : bool
	{
		
		if($this->isPropagationStopped()) {
			return false;
		}

		$handler = $this->getHandler();
		$event = $this->getEvent();
		$attributes = array_merge($this->getAttributes(),$arguments);
		if(is_callable($handler)) {
			$this->response = $handler($this, $event, $attributes);
		} elseif(is_array($handler)) {
			if(sizeof($handler) < 2) {
				throw new InvalidArgumentException("Event Handler size array invalid.");
				
			}
			if(!is_object($handler[0]) or !is_string($handler[1])) {
				throw new InvalidArgumentException("Invalid type arguments array handler");
			}
			$object = $handler[0];
			$method = "on".$handler[1]; 
			if(!method_exists($object, $method)) {
				throw new NotFoundException("`{$method}` in Subscribe not found.");
			}
			$this->response = $object->$method($this,$event, $attributes);
		}
		 elseif(is_string($handler)){
			$handler = explode("@", $handler);
			$object = new $handler[0];
			$method = "on".$handler[1]; 
			if(!method_exists($object, $method)) {
				throw new NotFoundException("`{$method}` in Subscribe not found.");
			}
			$this->response = $object->$method($this,$event, $attributes);
		}


		if($this->response === false && $this->isCancelable()) {
			$this->stopPropagation();
		}

		return true;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function isCancelable() : bool
    {
        return $this->cancelable;
    }

    public function setCancelable(bool $cancelable) : void
    {
        $this->cancelable = $cancelable;
    }
}