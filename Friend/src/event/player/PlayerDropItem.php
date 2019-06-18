<?php
namespace event\player;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\item\Item;

use api\FriendApi;

class PlayerDropItem implements Listener
{
	private $hasTag = false;
	function onDrop(PlayerDropItemEvent $event)
	{
		$item = $event->getItem();
		if ($item->getCustomName() === TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . FriendApi::$instance->getChatPartner($event->getPlayer()->getName()))
			$event->setCancelled();
	}

	function onDeath(PlayerDeathEvent $event)
	{
		$drops = $event->getDrops();
		$item = Item::get(421)->setCustomName(TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . FriendApi::$instance->getChatPartner($event->getPlayer()->getName()));

		if (in_array($item, $drops)) {
			for ($i = 0; $i <= count($drops); $i++) {
				if (array_search($item, $drops) !== $i)
					continue;
				unset($drops[$i]);
			}
			$event->setDrops($drops);
			$this->hasTag[$event->getPlayer()->getName()] = true;
		}
	}

	function onRespawn(PlayerRespawnEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();

		if ($this->hasTag[$name]) {
			$item = Item::get(421)->setCustomName(TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . FriendApi::$instance->getChatPartner($name));
			$player->getInventory()->addItem($item);
			$this->hasTag[$name] = false;
		}
	}

	function onTransaction(InventoryTransactionEvent $event)
	{
		foreach ($event->getTransaction()->getActions() as $action) {
			if ($action->getSourceItem()->getId() === 421 && $action->getSourceItem()->getCustomName() === TextFormat::YELLOW . "FriendChat " . TextFormat::WHITE . FriendApi::$instance->getChatPartner($event->getTransaction()->getSource()->getName())) {
				$event->setCancelled();
			}
		}
	}
}
