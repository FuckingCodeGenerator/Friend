<?php
namespace event\form\receive;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\element\Input;
use api\FriendApi;

class friend_receive
{
	function receiveResponse(Player $player, ?int $key)
	{
		$friend_api = FriendApi::$instance;
		$name = $player->getName();

		if (FormApi::formCancelled($key))
			return;

		switch ($key) {
			case 0:
				$friends = [];
		        $form = FormApi::makeListForm([$receive = new online_receive, "receiveResponse"]);
				foreach ($friend_api->getOnlineFriends($name) as $friend) {
			        $form->addButton(new Button($friend));
			        $friends[] = $friend;
				}
				$receive->setFriendList($friends);
				$form->addButton(new Button("戻る"))
			        ->setTitle(TextFormat::BOLD . TextFormat::DARK_GREEN . "ONLINE FRIENDS")
			        ->sendToPlayer($player);
				break;
			case 1:
				FormApi::makeCustomForm([new send_request_receive, "receiveResponse"])
					->addElement(new Input("プレイヤー名を入力してください", "プレイヤー名..."))
					->setTitle("フレンド申請")
					->sendToPlayer($player);
				break;
			case 2:
				$requests = [];
		        $form = FormApi::makeListForm([$receive = new request_receive, "receiveResponse"]);
		        foreach ($friend_api->getFriendRequests($name) as $request) {
		        	$form->addButton(new Button($request));
		        	$requests[] = $request;
		        }
		        $receive->setRequests($requests);
		        $form->addButton(new Button("戻る"))
			    	->setTitle(TextFormat::BLUE . TextFormat::BOLD . "フレンドリクエスト")
			    	->sendToPlayer($player);
				break;
			case 3:
				$requests = [];
		        $form = FormApi::makeListForm([$receive = new sent_request_receive, "receiveResponse"]);
		        foreach ($friend_api->getFriendRequests($name, true) as $request) {
		        	$form->addButton(new Button($request));
		        	$requests[] = $request;
		        }
		        $receive->setRequests($requests);
		        $form->addButton(new Button("戻る"))
			    	->setTitle(TextFormat::BLUE . TextFormat::BOLD . "申請中のフレンドリクエスト")
			    	->sendToPlayer($player);
				break;
			case 4:
		        $form = FormApi::makeListForm([new list_receive, "receiveResponse"]);
		        foreach ($friend_api->getFriendList($name) as $friend => $from) {
		        	$form->addButton(new Button($friend));
		        }
		        $form->addButton(new Button("戻る"))
			    	->setTitle(TextFormat::BLUE . TextFormat::BOLD . "フレンドリスト")
			    	->sendToPlayer($player);
				break;
		}
	}
}