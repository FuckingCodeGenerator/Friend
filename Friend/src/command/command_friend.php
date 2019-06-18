<?php
namespace command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class command_friend extends Command
{
	use \api\send_main_ui;

	const CMD_FRIEND_COMMAND = 'friend';
	const CMD_FRIEND_DESCRIPTION = "Friend のメニューを表示します";
	const CMD_FRIEND_USAGE = "/friend";

    function __construct()
    {
        parent::__construct(self::CMD_FRIEND_COMMAND, self::CMD_FRIEND_DESCRIPTION, self::CMD_FRIEND_USAGE);
        $this->setPermission("friend.command.friend");
    }

    function execute(CommandSender $sender, string $label, array $args): bool
    {
    	$this->sendUi($sender);
	    return true;
    }
}
