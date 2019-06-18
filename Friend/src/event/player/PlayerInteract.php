<?php
namespace event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Button;

use api\FriendApi;

class PlayerInteract implements Listener
{
	function onInteract(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$friend_api = FriendApi::$instance;

		if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_AIR)
			return;

		if ($player->getInventory()->getItemInHand()->getCustomName() === TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . $friend_api->getChatPartner($name)) {
			$friends = [];
	        $form = FormApi::makeListForm([$receive = new \event\form\receive\friend_chat_receive, "receiveResponse"]);
			foreach ($friend_api->getOnlineFriends($name) as $friend) {
		        $form->addButton(new Button($friend));
		        $friends[] = $friend;
			}
			$receive->setFriendList($friends);
		        $form->setTitle(TextFormat::BOLD . TextFormat::DARK_GREEN . "フレンドチャットの送信先を指定")
		        ->sendToPlayer($player);
		}
	}
}