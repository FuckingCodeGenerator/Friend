<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\Label;

use api\FriendApi;

class request_receive
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
        FormApi::makeModalForm([$receive = new request_re_receive, "receiveResponse"])
        	->setButtonText(true, TextFormat::DARK_GREEN . "承認")
        	->setButtonText(false, TextFormat::DARK_RED . "拒否")
        	->setContent("本当に " . $target_name . " とフレンドになりますか?\nフレンドになると様々な機能がフレンド間で使えるようになります。\nフレンドはメニューからいつでも解除ができます。")
	        ->setTitle(TextFormat::DARK_GREEN . $target_name . " からのフレンドリクエスト")
	        ->sendToPlayer($player);
        $receive->setRequests($target_name);
	}
}