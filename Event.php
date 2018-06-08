<?php

namespace Sweetkit\Foundation\Event;

use Sweetkit\Foundation\Event\{EventInterface,Listener,PriorityCollection};
use Sweetkit\Foundation\Event\Exceptions\{NotFoundException, InvalidArgumentException, NotCancelableException};

class Event
{
    protected $name;
    protected $listeners = [];
    protected $isPropagation = [];
    const LOW = 1;
    const NORMAL = 2;
    const HIGH = 3;
    const DELIMITER = ":";

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function clearListeners() : void
    {
        $this->listeners = [];
    }

    public function getPropagation(string $name) : bool
    {
        return $this->isPropagation[$name];
    }

    public function setPropagation(string $name, bool $propagation) : void 
    {
        $this->isPropagation[$name] = $propagation;
    }

    public function addListener(Listener $listener, int $priority) : void
    {
        $name = $listener->getName();
        if(!$this->issetListener($name)){
            $this->listeners[$name] = new PriorityCollection;
        }
        if(!isset($this->isPropagation[$name])) {
            $this->isPropagation[$name] = false;
        }
        $this->listeners[$name]->add($listener, $priority);
    }

    public function issetListener(string $name) : bool
    {
        return isset($this->listeners[$name]);
    }
    public function getListeners(string $name) : PriorityCollection
    {
        if(!$this->issetListener($name)) {
            throw new NotFoundException("Listener `{$name}` - not found.");
        }
        return $this->listeners[$name];
    }

    /**
     * Get event name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set the event name
     *
     * @param  string $name
     * @return void
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function execute(string $listenerName, array $arguments) : array
    {
        $response = [];
        
        $listeners = $this->getListeners($listenerName);
        if(sizeof($listeners) == 0) return [];
        foreach ($listeners as $listener) {
           if($listener->execute($arguments)) {
            $response[] = $listener->getResponse();
           }
           
        }
        return $response;
    }


}