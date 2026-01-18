<?php

namespace KaitoShirakawa\HealthDisplay;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param EntityDamageEvent $event
	 *
	 * @priority MONITOR
	 */
	public function onEntityDamage(EntityDamageEvent $event){
		if($event->isCancelled()){
			return;
		}

		$player = $event->getEntity();
		if($player instanceof Player){
			$this->displayHealth($player, max($player->getHealth() - round($event->getFinalDamage()), 0));
		}
	}

	/**
	 * @param EntityRegainHealthEvent $event
	 *
	 * @priority MONITOR
	 */
	public function onEntityRegainHealth(EntityRegainHealthEvent $event){
		if($event->isCancelled()){
			return;
		}

		$player = $event->getEntity();
		if($player instanceof Player){
			$this->displayHealth($player, min($player->getHealth() + $event->getAmount(), $player->getMaxHealth()));
		}
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority MONITOR
	 */
	public function onPlayerJoin(PlayerJoinEvent $event){
		$this->displayHealth($event->getPlayer(), $event->getPlayer()->getHealth());
	}

	/**
	 * @param PlayerRespawnEvent $event
	 *
	 * @priority MONITOR
	 */
	public function onPlayerRespawn(PlayerRespawnEvent $event){
		$this->displayHealth($event->getPlayer(), $event->getPlayer()->getMaxHealth());
	}

	private function displayHealth(Player $player, int $health){
		$name = explode(PHP_EOL, $player->getNameTag())[0];
		$name .= PHP_EOL . str_repeat(" ", strlen($name) / 3) . TextFormat::RESET . $health . TextFormat::RED . " ♥";

		$player->setNameTag($name);
	}
}
