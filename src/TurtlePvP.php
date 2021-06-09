<?php

namespace Core;

use Core\Functions\customTask;
use Core\Games\FFA;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent, PlayerChatEvent, PlayerCreationEvent, PlayerMoveEvent};
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\EntityDamageByEntityEvent;
use Core\Game\{Modes, ModesManager, Games, GamesManager};
use Core\Errors;
use Core\Functions\deleteBlock;

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

    public function playerClass(PlayerCreationEvent $e){
        $e->setPlayerClass(\TurtlePlayer::class);
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
                            $e->setCancelled();
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
                $e->setCancelled();
            }
          }
        }

        public function onBreak(BlockBreakEvent $e){

        if($e->getPlayer()->getCurrentMinigame() != Games::KBFFA) {
            $e->setCancelled();
        }

        }

        public function onPlace(BlockPlaceEvent $e){

        if($e->getPlayer()->getCurrentMinigame() == Games::KBFFA){
        $e->getPlayer()->getLevel()->broadcastLevelEvent($e->getBlock(), LevelEventPacket::EVENT_BLOCK_START_BREAK, (int) 20 * 10);
        $this->getScheduler()->scheduleDelayedTask(new deleteBlock($e->getBlock(), $e->getPlayer()->getLevel()), 20 * 10);

        } else {
            $e->setCancelled();
        }

        }

        public function cancelHit(EntityDamageByEntityEvent $e){

        if($e->getDamager()->getCurrentMinigame() == "lobby"){
            $e->setCancelled();

        }
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

         public function onHit(EntityDamageByEntityEvent $e){
         $d = $e->getDamager();
         $p = $e->getEntity();
         $p->setTagged($d);
         $task = $p->setTagged(null);
         $this->getScheduler()->scheduleDelayedTask(new customTask($task), 20 * 10);
         }

        public function onMove(PlayerMoveEvent $e)
        {
            if (is_null($e->getPlayer()->getKB())) {
                $e->getPlayer()->sendMessage("Error encountered. ERROR CODE 5: " . Errors::CODE_5);
                $e->setCancelled();
            }
        }

    }