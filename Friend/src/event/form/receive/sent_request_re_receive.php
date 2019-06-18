<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;

use api\FriendApi;

class sent_request_re_receive implements \api\type\NoticeType
{
	use \api\send_main_ui;

	private $target_name;

	function setRequests(string $target_name)
	{
		$this->target_name = $target_name;
	}

	function receiveResponse(Player $player, bool $response)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if ($response) {
			$friend_api->removeFriendRequest($name, $this->target_name);
			$player->sendMessage(TextFormat::GREEN . $this->target_name . " へのフレンドリクエストをキャンセルしました。");
		} else {
			$this->sendUi($player);
		}
	}
}
