<?php
namespace event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoin implements Listener, \api\type\NoticeType
{
	function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$friend_api = \api\FriendApi::$instance;
		$friend_api->createAccount($name);
		$friend_api->notice($name, self::NOTICE_TYPE_JOIN);
		if (!empty($requests = $friend_api->getFriendRequests($name))) {
			$player->sendMessage("[Friend] " . count($requests) . "件のフレンドリクエストが届いています。");
			$player->sendMessage("[Friend] フレンドリクエストの拒否承認は /friend メニューから行えます。");
		}
	}
}
