<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;

use api\FriendApi;

class list_re_receive implements \api\type\NoticeType
{
	use \api\send_main_ui;

	private $friend_name;

	function __construct(string $friend_name)
	{
		$this->friend_name = $friend_name;
	}

	function receiveResponse(Player $player, ?int $key)
	{
		if (FormApi::formCancelled($key))
			return;

		if ($key === 0) {
	        FormApi::makeModalForm([$this, "removeFriendReceive"])
	        	->setButtonText(true, TextFormat::DARK_RED . "解除")
	        	->setButtonText(false, "キャンセル")
	        	->setContent("本当に " . $this->friend_name . " とのフレンドを解除しますか?\n解除しても再度フレンドになることができます。\n\nフレンドを解除しても相手には通知されません。")
		        ->setTitle(TextFormat::DARK_GREEN . $this->friend_name . " のフレンド解除")
		        ->sendToPlayer($player);
		} else {
			$this->sendUi($player);
		}
	}

	function removeFriendReceive(Player $player, bool $response)
	{
		if ($response) {
			FriendApi::$instance->removeFriend($player->getName(), $this->friend_name);
			$player->sendMessage(TextFormat::GREEN . $this->friend_name . " とのフレンドを解除しました。");
		} else {
			$this->sendUi($player);
		}
	}
}
