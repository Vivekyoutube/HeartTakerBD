<?php
namespace Bobydev\HeartTakerBD;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;
use pockemine\Server;

class HeartTakerBD extends PluginBase implements Listener {

    private $extraHearts = [];

    public function onEnable(): void {
    	$this->getLogger()->info("HeartTakerBD Made By Bobydev Is  Enabled");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onKill(EntityDamageByEntityEvent $event): void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        if($entity instanceof Player && $damager instanceof Player){
            if($entity->getHealth() - $event->getFinalDamage() <= 0){
                // Grant extra heart
                $this->extraHearts[$damager->getName()] = ($this->extraHearts[$damager->getName()] ?? 0) + 2;
                $damager->setMaxHealth($damager->getMaxHealth() + 2);
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(isset($this->extraHearts[$name])){
            // Remove extra heart
            $player->setMaxHealth($player->getMaxHealth() - $this->extraHearts[$name]);
            unset($this->extraHearts[$name]);
        }
    }
}
