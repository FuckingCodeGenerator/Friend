<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Button;

use api\FriendApi;

class list_receive
{
	use \api\send_main_ui;

	function receiveResponse(Player $player, ?int $key)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if (FormApi::formCancelled($key))
			return;

		if (empty($list = $friend_api->getFriendList($name)) || $key === count($list)) {
			$this->sendUi($player);
			return;
		}

		$friend_name = key(array_slice($list, $key, 1, true));
        FormApi::makeListForm([new list_re_receive($friend_name), "receiveResponse"])
	        ->addButton(new Button(TextFormat::DARK_RED . "フレンドを解除"))
			->addButton(new Button("戻る"))
	        ->setTitle(TextFormat::DARK_GREEN . $friend_name)
	        ->sendToPlayer($player);
	}
}
