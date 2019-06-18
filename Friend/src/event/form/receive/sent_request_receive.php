<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;

use api\FriendApi;

class sent_request_receive
{
	use \api\send_main_ui;

	private $requests;

	function setRequests(array $requests)
	{
		$this->requests = $requests;
	}

	function receiveResponse(Player $player, ?int $key)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if (FormApi::formCancelled($key))
			return;

		if (empty($this->requests) || $key === count($this->requests)) {
			$this->sendUi($player);
			return;
		}
		
		$target_name = current(array_slice($this->requests, $key, 1, true));
        FormApi::makeModalForm([new sent_request_re_receive($target_name), "receiveResponse"])
        	->setButtonText(true,  "リクエストをキャンセル")
        	->setButtonText(false, "戻る")
	        ->setTitle(TextFormat::DARK_GREEN . $target_name . " へのフレンドリクエスト")
	        ->sendToPlayer($player);
	}
}