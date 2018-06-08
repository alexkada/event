<?php

namespace Sweetkit\Foundation\Event\Adapter;

use Sweetkit\Foundation\Event\Adapter;
use Sweetkit\Foundation\Event\Exceptions\{InvalidArgumentException,NotFoundException};

class ArrayFileAdapter extends Adapter
{
	public function load() : array
	{
		if(!file_exists($this->path)) {
			throw new NotFoundException("Event List file {$this->path} - not found.");
			
		}
		$data = require($this->path);
		if(!is_array($data)) {
			throw new InvalidArgumentException("Event list not array");
		}
		return $data;
	}

	
}