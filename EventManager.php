<?php

namespace Sweetkit\Foundation\Event;

use Sweetkit\Foundation\Event\{EventManagerInterface, EventInterface, Event, Listener, Subscriber};
use Sweetkit\Foundation\Event\Exceptions\{NotFoundException, InvalidArgumentException, NotCancelableException};





class EventManager
{
    protected $events = [];
    /**
     * Attaches a listener to an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @param int $priority the priority at which the $callback executed
     * @return bool true on success false on failure
     */
    



    public function __construct($file = null)
    {
        if(is_null($file)) return;
        $data = $file->getData();
        for($i = 0; $i < sizeof($data); $i++) {



            $event = $data[$i][0];
            $handler = $data[$i][1];
            $priority = Event::NORMAL;
            $attributes = [];
            $cancelable = true;
            if(isset($data[$i][2])) {
                $attributes = $data[$i][2];
            }
            if(isset($data[$i][4])) {
                $cancelable = $data[$i][4];
            }            
            if(isset($data[$i][3])) {
                if($data[$i][3] == "high") $priority = Event::HIGH;
                if($data[$i][3] == "normal") $priority = Event::NORMAL;
                if($data[$i][3] == "low") $priority = Event::LOW;
            }
            $this->listen($event,$handler ,$attributes,$priority);
        }
    }

    public function subscribe(Subscriber $event)
    {
        $event->subscribe($this);
    }

    public function listen(string $event, $handler, array $attributes = [], int $priority = Event::NORMAL, bool $cancelable = true) : void
    {
        $e = explode(Event::DELIMITER, trim(strtolower($event)));
        $event = $this->getEvent($e[0]);
        $listener = new Listener($event, $e[1], $handler, $cancelable, $attributes);
        $event->addListener($listener,$priority);
    }


    public function issetEvent(string $name) : bool
    {
        return isset($this->events[$name]);
    }

    public function getEvent(string $name) : Event
    {
        if(!$this->issetEvent($name)) {
            $this->events[$name] = new Event($name);
        }
        return $this->events[$name];
    }


    // /**
    //  * Clear all listeners for a given event
    //  *
    //  * @param  string $event
    //  * @return void
    //  */
    // public function clearListeners(string $name) : void
    // {
    //     $this->getEvent($name)->clearListeners();
    // }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @return mixed
     */
    public function fire(string $name, array $arguments = []) : array
    {
        $listenerName = false;
        $eventName = "";
        if(strpos(trim(strtolower($name)),Event::DELIMITER) === false){
            throw new InvalidArgumentException("Invalid name event `{$name}` - not found `:`");
        } 

        $e = explode(Event::DELIMITER, trim(strtolower($name)));
        $eventName = $e[0];
        $listenerName = $e[1];
        
        $event = $this->getEvent($eventName);
        return $event->execute($listenerName, $arguments);
    }
}

