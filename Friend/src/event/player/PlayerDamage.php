<?php
namespace event\player;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use api\FriendApi;

class PlayerDamage implements Listener
{
	function onDamage(EntityDamageByEntityEvent $event)
	{
		if (FriendApi::$instance->getConfig()["friendly_fire"])
			return;

		$entity = $event->getEntity();
		$damager = $event->getDamager();

		if ($entity instanceof Player && $damager instanceof Player) {
			$player = $entity;

			if (FriendApi::$instance->isFriend($player->getName(), $damager->getName())) {
				$damager->sendMessage(TextFormat::RED . "[Friend] フレンドを攻撃してはいけません!");
				$event->setCancelled();
			}
		}

	}
}
