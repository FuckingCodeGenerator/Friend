<?php
namespace command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;

use api\FriendApi;

class command_chat extends Command
{
    const CMD_FRIEND_CHAT_COMMAND = 'friendchat';
    const CMD_FRIEND_CHAT_DESCRIPTION = "フレンドチャットと設定ができます。";
    const CMD_FRIEND_CHAT_USAGE = "/friendchat <get/remove>";

    function __construct()
    {
        parent::__construct(self::CMD_FRIEND_CHAT_COMMAND, self::CMD_FRIEND_CHAT_DESCRIPTION, self::CMD_FRIEND_CHAT_USAGE);
        $this->setPermission("friend.command.friendchat");
    }

    function execute(CommandSender $sender, string $label, array $args): bool
    {
        $name = $sender->getName();
        $item = Item::get(421)->setCustomName(TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . FriendApi::$instance->getChatPartner($name));
        if (!isset($args[0])) {
            $sender->sendMessage($this->getUsage());
            return false;
        }

        if ($args[0] === "get") {

            if ($sender->getInventory()->contains($item)) {
                $sender->sendMessage(TextFormat::YELLOW . "既にアイテムを所有しています。");
                return true;
            }
            if (!$sender->getInventory()->canAddItem($item)) {
                $sender->sendMessage(TextFormat::YELLOW . "インベントリに空きスロットがありません。");
                return true;
            }

            $sender->getInventory()->addItem($item);

        } elseif ($args[0] === "remove") {

            if (!$sender->getInventory()->contains($item)) {
                $sender->sendMessage(TextFormat::YELLOW . "アイテムを所有していません。");
                return true;
            }

            $sender->getInventory()->removeItem($item);

        } else {
            $sender->sendMessage($this->getUsage());
            return false;
        }

	    return true;
    }
}
