<?php
namespace event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use pocketmine\Server;

use api\FriendApi;

class PlayerChat implements Listener
{
	function onChat(PlayerChatEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$friend_api = FriendApi::$instance;

		if ($item = ($player->getInventory()->getItemInHand())->getCustomName() === TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . $friend_api->getChatPartner($name)) {
			$target = Server::getInstance()->getPlayer($friend = $friend_api->getChatPartner($name));
			if ($target === null) {
				$player->sendMessage(TextFormat::YELLOW . $friend . " さんは既にオフラインです。");
				$player->getInventory()->removeItem($item);
				return;
			}
			$message = $event->getMessage();
			$event->setCancelled();
			$target->sendMessage(TextFormat::YELLOW . "[Friend Chat " . TextFormat::WHITE . $name . TextFormat::YELLOW . "] " . TextFormat::RESET . $message);
			$player->sendMessage(TextFormat::YELLOW . "[Friend Chat " . TextFormat::WHITE . $name . TextFormat::YELLOW . "] " . TextFormat::RESET . $message);
		}		
	}
}
