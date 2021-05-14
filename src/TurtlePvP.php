<?php

namespace Core;

use Core\Games\FFA;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent, PlayerChatEvent, PlayerCreationEvent};
use pocketmine\event\block\{BlockBreakEvent};
use pocketmine\event\entity\EntityDamageByEntityEvent;
use Core\Game\{Modes, ModesManager, Games, GamesManager};
use Core\Errors;

class Core extends PluginBase implements Listener{

    private static $instance;
    public $ffa;
    public $modes;
    public $games;

    public function onEnable():void{
        self::$instance = $this;
        $this->ffa = FFA::class;
        $this->modes = ModesManager::class;
        $this->games = GamesManager::class;
    }


    public function getModesManager(){
        return $this->modes;
    }

    public function getGamesManager(){
        return $this->games;
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
                        if($victim->getCurrentMinigame() != "lobby" or $victim->getCurrentGamemode() != "lobby") {
                            $victim->intializeRespawn($victim->getCurrentGamemode());
                        }else{
                            $victim->sendMessage("Error encountered. ERROR CODE 4: ".Errors::CODE_4);
                        }
                    }
                }
            }
        }

        public function onChat(PlayerChatEvent $e){
        if($e->getPlayer()->getIsRespawning() == true) {
            if($e->getMessage() == "lobby"){
                $e->getPlayer()->initializeLobby();
            }
          }
        }

        public function onBreak(BlockBreakEvent $e){
        $e->setCancelled();
        }

        public function setKB(EntityDamageByEntityEvent $e){
        if($e->getDamager()->getCurrentMinigame() == Games::FFA){
            if($e->getDamager()->getCurrentGamemode() == Modes::FIST){
                $e->getDamager()->setMotion(new Vector3(0.405, 0.370, 0.405));
            }elseif($e->getDamager()->getCurrentGamemode() == Modes::SUMO){
                $e->getDamager()->setMotion(new Vector3(0.385, 0.380, 0.385));
            }
          }
        }

        public function setAttackTime(EntityDamageEvent $e){
            if($e->getDamager()->getCurrentMinigame() == Games::FFA){
                if($e->getDamager()->getCurrentGamemode() == Modes::FIST){
                    $e->setAttackCooldown(8);
                }elseif($e->getDamager()->getCurrentGamemode() == Modes::SUMO){
                    $e->setAttackCooldown(10);
                }
            }
        }

}