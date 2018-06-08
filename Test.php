<?php
namespace Sweetkit\Foundation\Event;


class Test
{
	function onSendUser($listener,$event,$attributes)
	{
		echo "onSendUser";
	}
}