<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;

use api\FriendApi;

class request_re_receive implements \api\type\NoticeType
{
	private $from_name;

	function setRequests(string $from_name)
	{
		$this->from_name = $from_name;
	}

	function receiveResponse(Player $player, bool $response)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if ($response) {
			$friend_api->acceptFriendRequest($name, $this->from_name);
			$friend_api->notice($name, self::NOTICE_TYPE_REQUEST_ACCEPTED, $this->from_name);
			$player->sendMessage(TextFormat::GREEN . $this->from_name . " とフレンドになりました。");
		} else {
			$friend_api->removeFriendRequest($name, $this->from_name);
			$player->sendMessage(TextFormat::GREEN . $this->from_name . " からの申請を拒否しました。");
		}
	}
}
