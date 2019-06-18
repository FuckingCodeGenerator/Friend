<?php

use pocketmine\plugin\PluginBase;
use api\FriendApi;

define("PLUGIN_VERSION", '1.0.07');

class Main extends PluginBase
{
	function onEnable()
	{
		$this->init();


		$this->getLogger()->info("Friend が起動しました。");
	}

	function onDisable()
	{
		FriendApi::$instance->save();
	}

	private function init(): void
	{
		\tokyo\pmmp\libform\FormApi::register($this);
		new FriendApi($this);

        $command_map = $this->getServer()->getCommandMap();
        $command_map->register("friend", new \command\command_friend);
        $command_map->register("friendchat", new \command\command_chat);

	    $this->getServer()->getPluginManager()->registerEvents(new \event\player\PlayerInteract, $this);
	    $this->getServer()->getPluginManager()->registerEvents(new \event\player\PlayerDropItem, $this);
	    $this->getServer()->getPluginManager()->registerEvents(new \event\player\PlayerDamage, $this);
	    $this->getServer()->getPluginManager()->registerEvents(new \event\player\PlayerChat, $this);
	    $this->getServer()->getPluginManager()->registerEvents(new \event\player\PlayerJoin, $this);
	    $this->getServer()->getPluginManager()->registerEvents(new \event\player\PlayerQuit, $this);
	}
}
