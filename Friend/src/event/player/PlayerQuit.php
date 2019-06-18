<?php
namespace event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuit implements Listener, \api\type\NoticeType
{
	function onQuit(PlayerQuitEvent $event)
	{
		$name = $event->getPlayer()->getName();
		\api\FriendApi::$instance->notice($name, self::NOTICE_TYPE_QUIT);
	}
}
