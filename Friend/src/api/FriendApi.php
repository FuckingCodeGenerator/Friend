<?php
namespace api;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class FriendApi implements \api\type\NoticeType
{
	static $instance;
	private $config, $friend_data_file, $friend_data, $friend_request_file, $friend_request, $friend_chat_file, $friend_chat;

	function __construct(\Main $owner)
	{
		$this->initFiles($owner);
		self::$instance = $this;
	}

	private function initFiles($owner): void
	{
		$owner->saveResource("Config.yml", false);
		$this->config = (new Config($owner->getDataFolder() . "Config.yml", Config::YAML))->getAll();
		$this->friend_data_file = new Config($owner->getDataFolder() . "FriendData.yml", Config::YAML);
		$this->friend_request_file = new Config($owner->getDataFolder() . "FriendRequest.yml", Config::YAML);
		$this->friend_chat_file = new Config($owner->getDataFolder() . "FriendChat.yml", Config::YAML);
		$this->friend_chat = $this->friend_chat_file->getAll();
		$this->friend_data = $this->friend_data_file->getAll();
		$this->friend_request = $this->friend_request_file->getAll();	
	}

	function save(): void
	{
		$this->friend_data_file->setAll($this->friend_data);
		$this->friend_request_file->setAll($this->friend_request);
		$this->friend_chat_file->setAll($this->friend_chat);
		$this->friend_data_file->save();
		$this->friend_request_file->save();
		$this->friend_chat_file->save();
	}

	function notice(string $name, int $type, string $target = "")
	{
		switch ($type) {
			case self::NOTICE_TYPE_JOIN:
				foreach ($this->getOnlineFriends($name) as $friend) {
					Server::getInstance()->getPlayer($friend)->sendMessage(TextFormat::BOLD . "[" . TextFormat::GREEN . "+" . TextFormat::WHITE . "] " . $name . " さんがオンラインになりました。");
				}
				return;
			case self::NOTICE_TYPE_QUIT:
				foreach ($this->getOnlineFriends($name) as $friend) {
					Server::getInstance()->getPlayer($friend)->sendMessage(TextFormat::BOLD . "[" . TextFormat::RED . "-" . TextFormat::WHITE . "] " . $name . " さんがオフラインになりました。");
				}
				return;
			case self::NOTICE_TYPE_REQUEST:
				$player = Server::getInstance()->getPlayer($name);
				if ($player->getName() === $target)
					return;
				if ($player !== null) {
					$player->sendMessage(TextFormat::GRAY . "[Friend] " . $target . " さんがあなたとフレンドになりたがっています。");
					$player->sendMessage(TextFormat::GRAY . "[Friend] フレンドリクエストの拒否承認は /friend メニューから行えます。");
				}
				return;
			case self::NOTICE_TYPE_REQUEST_ACCEPTED:
				$player = Server::getInstance()->getPlayer($target);
				if ($player !== null)
					$player->sendMessage(TextFormat::GREEN . "[Friend] " . $name . " さんとフレンドになりました。");
				return;
		}
	}

	function getConfig(): array
	{
		return $this->config;
	}

	function getChatPartner(string $name): string
	{
		return isset($this->friend_chat[$name]) ? $this->friend_chat[$name] : "";
	}

	function setChatPartner(string $name, string $friend): void
	{
		$this->friend_chat[$name] = $friend;
	}

	function createAccount(string $name): void
	{
		if ($this->existsAccount($name))
			return;

		$this->friend_request[$name] = [];
		$this->friend_data[$name] = [];
	}

	function existsAccount(string $name): bool
	{
		return isset($this->friend_data[$name]);
	}

	function addFriend(string $name, string $friend): void
	{
		$this->friend_data[$name][$friend] = true;
		$this->friend_data[$friend][$name] = true;
	}

	function removeFriend(string $name, string $friend): void
	{
		unset($this->friend_data[$name][$friend]);
		unset($this->friend_data[$friend][$name]);
	}

	function isFriend(string $name, string $target): bool
	{
		return isset($this->friend_data[$name][$target]);
	}

	function getFriendList(string $name): array
	{
		return $this->friend_data[$name];
	}

	function getOnlineFriends(string $name): array
	{
		$result = [];
		foreach (Server::getInstance()->getOnlinePlayers() as $online) {
			if (in_array($online->getName(), array_keys($this->getFriendList($name)), true)) {
				$result[] = $online->getName();
			}
		}
		return $result;
	}

	function getFriendRequests(string $name, bool $sent = false): array
	{
		$result = [];
		foreach ($this->friend_request[$name] as $user => $from) {
			if ($from === $sent)
				$result[] = $user;
		}
		return $result;
	}

	function addFriendRequest(string $name, string $from): void
	{
		if (isset($this->friend_request[$name][$from]))
			return;

		$this->friend_request[$name][$from] = false;
		$this->friend_request[$from][$name] = true;
	}

	function removeFriendRequest(string $name, string $from): void
	{
		unset($this->friend_request[$name][$from]);
		unset($this->friend_request[$from][$name]);
	}

	function acceptFriendRequest(string $name, string $from): void
	{
		$this->removeFriendRequest($name, $from);
		$this->addFriend($name, $from);
	}
}
