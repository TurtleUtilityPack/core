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
use pocketmine\event\player\{PlayerJoinEvent, PlayerChatEvent, PlayerCreationEvent, PlayerMoveEvent, PlayerQuitEvent};
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\EntityDamageByEntityEvent;
use Core\Game\{Game, Modes, ModesManager, Games, GamesManager};
use Core\Errors;
use Core\Events\TurtleGameEndEvent;
use Core\Functions\deleteBlock;

class Core extends PluginBase implements Listener{

    private static $instance;
    public $ffa;
    public $mode;
    public $game;

    public $runningGames = [];

    public function onEnable():void{
        self::$instance = $this;
        $this->ffa = FFA::class;
        $this->mode = ModesManager::class;
        $this->game = GamesManager::class;

        $fist = new Game(null, Games::FFA, Modes::FIST);
        $sumo = new Game(null, Games::FFA, Modes::SUMO);
        $this->addRunningGame($fist, 'fist');
        $this->addRunningGame($sumo, 'sumo');

    }

    public function playerClass(PlayerCreationEvent $e){
        $e->setPlayerClass(\TurtlePlayer::class);
    }


    public function getModesManager(){
        return $this->mode;
    }

    public function getGamesManager(){
        return $this->game;
    }

    public function getRunningGames(){
        return $this->runningGames;
    }

    public function addRunningGame(Game $game, string $name){
    $this->runningGames[$name] = $game;
    }

    public function getGame(string $name){
    return $this->runningGames[$name];
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
                        if($victim->getGame() != null) {
                            $victim->intializeRespawn($victim->getGame());
                            $e->setCancelled();
                            $victim->setTagged(null);
                            $e->getEntity()->setTagged(null);
                        }else{
                            $victim->sendMessage("Error encountered. ERROR CODE 4: ".Errors::CODE_4);
                        }
                    }
                }
            }
        }

        public function onChat(PlayerChatEvent $e){

        if($e->getPlayer()->getIsRespawning()) {
            if($e->getMessage() == "lobby"){

                $e->getPlayer()->initializeLobby();
                $players = [$e->getPlayer(), $e->getPlayer()->getTagged()];
                foreach ($players as $player)
                $event = new TurtleGameEndEvent($player, $e->getPlayer()->getTagged(), $e->getPlayer());
                $event->call();
                $e->setCancelled();

            }
          }
        }

        public function onBreak(BlockBreakEvent $e){

        if($e->getPlayer()->getGame()->getType() != Games::KBFFA) {
            $e->setCancelled();
        }

        }

        public function onPlace(BlockPlaceEvent $e){

        if($e->getPlayer()->getGame()->getType() == Games::KBFFA){
        $e->getPlayer()->getLevel()->broadcastLevelEvent($e->getBlock(), LevelEventPacket::EVENT_BLOCK_START_BREAK, (int) 20 * 10);
        $this->getScheduler()->scheduleDelayedTask(new deleteBlock($e->getBlock(), $e->getPlayer()->getLevel()), 20 * 10);

        } else {
            $e->setCancelled();
        }

        }

        public function cancelHit(EntityDamageByEntityEvent $e){

        if($e->getDamager()->getGame() == null){
            $e->setCancelled();

        }
        }

        public function setKB(EntityDamageByEntityEvent $e){


        if($e->getDamager()->getGame()->getType() == Games::FFA){
            if($e->getDamager()->getGame()->getMode() == Modes::FIST){
                $e->getDamager()->setMotion(new Vector3(0.405, 0.370, 0.405));
            }elseif($e->getDamager()->getGame()->getMode() == Modes::SUMO){
                $e->getDamager()->setMotion(new Vector3(0.385, 0.380, 0.385));
            }
          }


        }

        public function setAttackTime(EntityDamageEvent $e){


            if($e->getDamager()->getGame()->getType() == Games::FFA){
                if($e->getDamager()->getGame()->getMode() == Modes::FIST){
                    $e->setAttackCooldown(8);
                }elseif($e->getDamager()->getGame()->getMode() == Modes::SUMO){
                    $e->setAttackCooldown(10);
                }
            }
         }

         public function onHit(EntityDamageByEntityEvent $e){
         $d = $e->getDamager();
         $p = $e->getEntity();
         $p->setTagged($d);
         $p->sendMessage("You're now combat logged.");
         $task = $p->setTagged(null);
         $this->getScheduler()->scheduleDelayedTask(new customTask($task), 20 * 10);
         }

         public function onLeave(PlayerQuitEvent $e){
         //TODO: Combat Logger, gib kills to who tagged
         }

        public function onMove(PlayerMoveEvent $e)
        {
            if (is_null($e->getPlayer()->getKB())) {
                $e->getPlayer()->sendMessage("Error encountered. ERROR CODE 5: " . Errors::CODE_5);
                $e->setCancelled();
            }
        }

    }