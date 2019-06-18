<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Button;

use api\FriendApi;

class online_receive
{
	use \api\send_main_ui;

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

		if (empty($friend_api->getOnlineFriends($name)) || $key === count($friend_api->getOnlineFriends($name))) {
			$this->sendUi($player);
			return;
		}

		$friend_name = current(array_slice($this->friends, $key, 1, true));
        FormApi::makeListForm([new online_re_receive($friend_name), "receiveResponse"])
	        ->addButton(new Button("テレポート"))
			->addButton(new Button("チャット"))
			->addButton(new Button("戻る"))
	        ->setTitle(TextFormat::DARK_GREEN . $friend_name)
	        ->sendToPlayer($player);
	}
}