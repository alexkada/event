<?php

namespace Sweetkit\Foundation\Event;

class PriorityCollection implements \Iterator, \Countable
{
    /**
     * Elements collection
     * @var array
     */
    public $elements = [];
    /**
     * Priorities elements
     * @var array
     */
    public $priority = [];
    /**
     * Priorities using?
     * @var bool
     */
    protected $isPriority = true;
    /**
     * Remove element
     * @param  int $key
     * @return void
     */
    
    public function __construct(array $elements = [], array $priority = [])
    {
    	if(sizeof($elements) !== sizeof($priority)) {
    		
    	}
    	$this->elements = $elements;
    	$this->priority = $priority;
    }
    public function remove($key)
    {
        unset($this->elements[$key]);
        unset($this->priority[$key]);
        $this->elements = array_values($this->elements);
        $this->priority = array_values($this->priority);
        $this->sort();
    }
    /**
     * Setup using priorities or getting setup
     * @param  mixed $priority
     * @return mixed
     */
    public function priority($priority = null)
    {
        if (is_bool($priority) === true) {
            $this->isPriority = $priority;   
        }
        if (is_null($priority) === true) {
            return $this->isPriority;
        }
        return null;
    }
    /**
     * Add element
     * @param mixed $elem
     * @param int $priority
     */
    public function add($elem, $priority)
    {
        $priority = intval($priority);
        array_push($this->elements, $elem);
        array_push($this->priority, $priority);
        $this->sort();
    }
    /**
     * Current Iterator
     * @return array
     */
    public function current()
    {
        return $this->elements[$this->key()];
    }
    /**
     * Sort elements
     * @return void
     */
    protected function sort()
    {
        if ($this->priority() === true) {
            arsort($this->priority);  
        } else {
            ksort($this->priority);
        }  
    }
    /**
     * Reset Iterator
     * @return void
     */
    public function rewind()
    {
        $this->sort();
        reset($this->priority);
    }
    /**
     * Valid Iterator
     * @return bool
     */
    public function valid()
    {
        return isset($this->priority[$this->key()]);
    }
    /**
     * Now key element Iterator
     * @return int
     */
    public function key()
    {
        return key($this->priority);
    }
    /**
     * Next Iterator
     * @return array
     */
    public function next()
    {

      $q =   next($this->priority);
      if($q == false) return false;
      return $this->elements[$this->key()];
    }

    public function getPriority()
    {
    	return $this->priority;
    }
    /**
     * Size collection Countable
     * @return int
     */
    public function count()
    {
        return sizeof($this->elements);
    }
    /**
     * Export array
     * @return array
     */
    public function export()
    {
        return $this->elements;
    }
}