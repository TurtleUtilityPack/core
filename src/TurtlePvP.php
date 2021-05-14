<?php

namespace Core;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Core extends PluginBase implements Listener{

    private static $instance;

    public function onEnable():void{
        self::$instance = $this;
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function onJoin(PlayerJoinEvent $e){
    $e->getPlayer()->teleport(new Vector3(0, 0, 0, 0, 0, $this->getServer()->getLevelByName("lobby")));
    }

        public function onDeath(EntityDamageByEntityEvent $e)
        {
            $victim = $e->getEntity();
            if ($victim instanceof Player) {
                if ($victim->isOnline()) {
                    if ($e->getFinalDamage() >= $victim->getHealth()) {
                        if($victim->getCurrentMinigame() != "lobby" or $victim->getCurrentGamemode() != "lobby")
                        $victim->intializeRespawn($victim->getCurrentGamemode());
                    }
                }
            }
        }

        public function onChat(PlayerChatEvent $e){
        if($e->getPlayer()->getIsRespawning() == true) {
            if($e->getContents() == "lobby"){
                $e->getPlayer()->setIsInLobby();
            }
         }
        }
}