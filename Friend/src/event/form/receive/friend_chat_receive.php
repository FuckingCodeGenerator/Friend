<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\item\Item;

use tokyo\pmmp\libform\FormApi;

use api\FriendApi;

class friend_chat_receive
{
	private $friends;

	function setFriendList(array $friends)
	{
		$this->friends = $friends;
	}

	function receiveResponse(Player $player, ?int $key)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if (FormApi::formCancelled($key))
			return;

		$friend_name = current(array_slice($this->friends, $key, 1, true));
		$item = Item::get(421)->setCustomName(TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . $friend_api->getChatPartner($name));
		$player->getInventory()->removeItem($item);
		$item = $item->setCustomName(TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . $friend_name);
		$player->getInventory()->addItem($item);
		$friend_api->setChatPartner($name, $friend_name);
		$player->sendMessage("フレンドチャットの送信先を " . $friend_name . " さんに設定しました。");
		$player->sendMessage("名札を捨てたい場合は /friendchat remove と実行してください。");
	}
}
