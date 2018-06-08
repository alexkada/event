<?php

namespace Sweetkit\Foundation\Event;

abstract class Adapter
{
	protected $path;
	protected $data;
	public function __construct(string $path)
	{
		$this->path = $path;
		$this->data = $this->load();
	}

	abstract public function load() : array;

	function getData()
	{
		return $this->data;
	}


}