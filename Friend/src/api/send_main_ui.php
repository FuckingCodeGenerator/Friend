<?php
namespace api;

use pocketmine\utils\TextFormat;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Button;

trait send_main_ui
{
	function sendUi(\pocketmine\Player $player): void
	{
    	$friend_api = FriendApi::$instance;
    	$name = $player->getName();
    	$friend_request_button = (count($friend_api->getFriendList($name)) >= $friend_api->getConfig()["max_friend"]) ? TextFormat::RED . "フレンド人数が最大です" : "フレンド申請";
    	$friend_request_button = ($friend_api->getConfig()["max_friend"] === -1) ? "フレンド申請" : $friend_request_button;
        FormApi::makeListForm([new \event\form\receive\friend_receive, "receiveResponse"])
	        ->addButton(new Button("オンラインのフレンド (" . count($friend_api->getOnlineFriends($name)) . ")"))
	        ->addButton(new Button($friend_request_button))
	        ->addButton(new Button("フレンドリクエスト (" . count($friend_api->getFriendRequests($name)) . ")"))
	        ->addButton(new Button("申請中のフレンドリクエスト (" . count($friend_api->getFriendRequests($name, true)) . ")"))
	        ->addButton(new Button("フレンドリスト (" . count($friend_api->getFriendList($name)) . " / " . (($friend_api->getConfig()["max_friend"] === -1) ? "無制限" : $friend_api->getConfig()["max_friend"]) . ")"))
	        ->setTitle(TextFormat::BOLD . TextFormat::DARK_GREEN . "Friend " . PLUGIN_VERSION)
	        ->sendToPlayer($player);
	}
}