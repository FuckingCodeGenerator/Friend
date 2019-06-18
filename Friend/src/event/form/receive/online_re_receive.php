<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Input;

use api\FriendApi;

class online_re_receive
{
	use \api\send_main_ui;

	private $friend_name;

	function __construct(string $friend_name)
	{
		$this->friend_name = $friend_name;
	}

	function receiveResponse(Player $player, ?int $key)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		switch ($key) {
			case 0:
				if (!$friend_api->getConfig()["teleport"]) {
					$player->sendMessage(TextFormat::YELLOW . "このサーバーではフレンドへのテレポートが無効化されています。");
					return;
				}
				if (($target = Server::getInstance()->getPlayer($this->friend_name)) === null) {
					$player->sendMessage(TextFormat::YELLOW . $this->friend_name . "さんは現在オフラインです。");
					return;
				}

				$player->teleport($target);
				$player->sendMessage("テレポートが完了しました。");
				return;
			case 1:
				FormApi::makeCustomForm([$this, "chatReceive"])
					->addElement(new Input("チャット:", "メッセージ..."))
					->setTitle($this->friend_name . " さんへチャットを送信")
					->sendToPlayer($player);
				return;
			case 2:
				$this->sendUi($player);
				return;
		}
	}

	function chatReceive(Player $player, ?array $response)
	{
		if (FormApi::formCancelled($response))
			return;
		if (($target = Server::getInstance()->getPlayer($this->friend_name)) === null) {
			$player->sendMessage(TextFormat::YELLOW . $this->friend_name . "さんは現在オフラインです。");
			return;
		}

		$target->sendMessage(TextFormat::YELLOW . "[Friend Chat " . TextFormat::WHITE . $player->getName() . TextFormat::YELLOW . "] " . TextFormat::RESET . $response[0]);
		$player->sendMessage(TextFormat::YELLOW . "[Friend Chat " . TextFormat::WHITE . $player->getName() . TextFormat::YELLOW . "] " . TextFormat::RESET . $response[0]);
	}
}
