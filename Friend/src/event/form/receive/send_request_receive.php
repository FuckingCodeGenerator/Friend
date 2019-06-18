<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;

use api\FriendApi;

class send_request_receive implements \api\type\NoticeType
{
	function receiveResponse(Player $player, ?array $response)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if (FormApi::formCancelled($response))
			return;

		$target = $response[0];

		if (empty($target)) {
			$player->sendMessage(TextFormat::YELLOW . "プレイヤー名を入力してください。");
			return;
		}
		if ($target === $name) {
			$player->sendMessage(TextFormat::YELLOW . "自分自身とフレンドになることはできません。");
			return;
		}
		if (!$friend_api->existsAccount($target)) {
			$player->sendMessage(TextFormat::YELLOW . $target . " さんのアカウントが見つかりませんでした。");
			return;
		}
		if ($friend_api->isFriend($name, $target)) {
			$player->sendMessage(TextFormat::YELLOW . $target . " さんとは既にフレンドになっています。");
			return;
		}
		if ($friend_api->getConfig()["max_friend"] !== -1) {
			if (count($friend_api->getFriendList($target)) >= $friend_api->getConfig()["max_friend"]) {
				$player->sendMessage(TextFormat::YELLOW . $target . " さんのフレンド数が最大数に達しています。");
				return;
			}
		}
		if (in_array($name, $friend_api->getFriendRequests($target), true)) {
			$player->sendMessage(TextFormat::YELLOW . "既にリクエストを送信済みです。");
			return;
		}

		$friend_api->addFriendRequest($target, $name);
		$friend_api->notice($target, self::NOTICE_TYPE_REQUEST, $name);
		$player->sendMessage("フレンドリクエストを送信しました。");
	}
}
