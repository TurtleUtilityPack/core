<?php

namespace Core;

use Core\BossBar\BossBar;
use Core\Events\TurtleGameEnterEvent;
use Core\Functions\CustomTask;
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
use Core\Functions\DeleteBlock;
use Party;

class Main extends PluginBase implements Listener{

    private static $instance;

    /**
     * @var FFA
     */
    public FFA $ffa;

    /**
     * @var ModesManager
     */
    public ModesManager $mode;

    /**
     * @var GamesManager
     */
    public GamesManager $game;

    /**
     * @var array|Game
     */
    public $runningGames = [];

    /**
     * @var Party|array
     */
    public $parties = [];


    /**
     *
     */
    public function onEnable():void{
        self::$instance = $this;

        $fist = new Game(null, Games::FFA, Modes::FIST, 'fist');
        $sumo = new Game(null, Games::FFA, Modes::SUMO, 'sumo');
        $this->addRunningGame($fist, 'fist-ffa');
        $this->addRunningGame($sumo, 'sumo-ffa');
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $eventHandler = new Events($this);
        $eventHandler->registerEvents();

    }


    /**
     * @param PlayerCreationEvent $e
     */
    public function playerClass(PlayerCreationEvent $e){
        $e->setPlayerClass(TurtlePlayer::class);
    }


    /**
     * @return mixed
     */
    public function getModesManager(){
        return $this->mode;
    }

    /**
     * @return mixed
     */
    public function getGamesManager(){
        return $this->game;
    }

    /**
     * @return array
     */
    public function getRunningGames(){
        return $this->runningGames;
    }

    /**
     * @param Game $game
     * @param string $name
     */
    public function addRunningGame(Game $game, string $name){
    $this->runningGames[$name] = $game;
    }


    /**
     * @param string $name
     * @return mixed
     */
    public function getGame(string $name){
    return $this->runningGames[$name];
    }


    /**
     * @return mixed
     */
    public static function getInstance(){
        return self::$instance;
    }

    /**
     * @param PlayerJoinEvent $e
     */
    public function onJoin(PlayerJoinEvent $e){
        $e->getPlayer()->teleport(new Vector3(0, 0, 0, 0, 0, $this->getServer()->getLevelByName("lobby")));
        try {
            $bossbar = new BossBar();
            $bossbar->setTitle("Turtle PvP " . $e->getPlayer()->getGame());
            $bossbar->addPlayer($e->getPlayer());
        } catch (\Exception $exception) {
            $bossbar = new BossBar();
            $bossbar->setTitle("Playing on turtle pvp");
            $bossbar->addPlayer($e->getPlayer());
        }
    }


    /**
     * @param EntityDamageByEntityEvent $e
     */
        public function onDeath(EntityDamageByEntityEvent $e)
        {
            $victim = $e->getEntity();
            if ($victim instanceof Player) {
                if ($victim->isOnline()) {
                    if ($e->getFinalDamage() >= $victim->getHealth()) {
                        if($victim->getGame() != null) {
                            if($victim instanceof TurtlePlayer) {
                                $victim->initializeRespawn($victim->getGame());
                                $e->setCancelled();
                                $victim->setTagged(null);
                                $e->getEntity()->setTagged(null);
                            }
                        }else{
                            $victim->sendMessage("Error encountered. ERROR CODE 4: ".Errors::CODE_4);
                        }
                    }
                }
            }
        }

    /**
     * @param TurtleGameEnterEvent $e
     */
    public function onEnter(TurtleGameEnterEvent $e){

        $game = $e->getGame();
        $minigame = $game->getType();
        $mode = $game->getMode();


        if (Main::getInstance()->getModesManager()->validate($mode) && Main::getInstance()->getGamesManager()->validate($minigame)) {
            if($minigame == Main::getInstance()->getGamesManager()::FFA) {
                    Main::getInstance()->getGamesManager()->getFFAManager()->initializeGame($this, $game);
                    $game->addPlayer($e->getPlayer());
            }elseif($minigame == Main::getInstance()->getGamesManager()::KBFFA){
                Main::getInstance()->getGamesManager()->getKBFFAManager()->initializeGame($this, $game);
                $game->addPlayer($e->getPlayer());
            }
            try {
                $bossbar = new BossBar();
                $bossbar->setTitle("Turtle PvP " . $e->getPlayer()->getGame());
                $bossbar->addPlayer($e->getPlayer());
            } catch (\Exception $exception) {
                $bossbar = new BossBar();
                $bossbar->setTitle("Playing on turtle pvp");
                $bossbar->addPlayer($e->getPlayer());
            }
        } else {
            $e->getPlayer()->sendMessage("Error encountered. ERROR CODE 3: " . Errors::CODE_3);
        }

    }

    /**
     * @param TurtleGameEndEvent $e
     */
    public function onLeave(TurtleGameEndEvent $e){

    $e->getGame()->removePlayer($e->getGamePlayers());
    //gib winner kills, etc.

    }

    /**
     * @param PlayerChatEvent $e
     */
        public function onChat(PlayerChatEvent $e){

        if($e->getPlayer()->getIsRespawning()) {
            if($e->getMessage() == "lobby"){

                $e->getPlayer()->initializeLobby();
                $players = [$e->getPlayer(), $e->getPlayer()->getTagged()];
                foreach ($players as $player)
                $event = new TurtleGameEndEvent($player, $e->getPlayer()->getTagged(), $e->getPlayer(), $e->getPlayer()->getGame());
                $event->call();
                $e->setCancelled();

            }
          }
        }

    /**
     * @param BlockBreakEvent $e
     */
        public function onBreak(BlockBreakEvent $e){

        if($e->getPlayer()->getGame()->getType() != Games::KBFFA) {
            $e->setCancelled();
        }

        }

    /**
     * @param BlockPlaceEvent $e
     */
        public function onPlace(BlockPlaceEvent $e){

        if($e->getPlayer()->getGame()->getType() == Games::KBFFA){
        $e->getPlayer()->getLevel()->broadcastLevelEvent($e->getBlock(), LevelEventPacket::EVENT_BLOCK_START_BREAK, (int) 20 * 10);
        $this->getScheduler()->scheduleDelayedTask(new DeleteBlock($e->getBlock(), $e->getPlayer()->getLevel()), 20 * 10);

        } else {
            $e->setCancelled();
        }

        }

    /**
     * @param EntityDamageByEntityEvent $e
     */
        public function cancelHit(EntityDamageByEntityEvent $e){

        if($e->getDamager()->getGame() == null){
            $e->setCancelled();

        }
        }

    /**
     * @param EntityDamageByEntityEvent $e
     */
        public function setKB(EntityDamageByEntityEvent $e){


        if($e->getDamager()->getGame()->getType() == Games::FFA){
            if($e->getDamager()->getGame()->getMode() == Modes::FIST){
                $e->getDamager()->setMotion(new Vector3(0.405, 0.370, 0.405));
            }elseif($e->getDamager()->getGame()->getMode() == Modes::SUMO){
                $e->getDamager()->setMotion(new Vector3(0.385, 0.380, 0.385));
            }
          }


        }

    /**
     * @param EntityDamageEvent $e
     */
        public function setAttackTime(EntityDamageEvent $e){


            if($e->getDamager()->getGame()->getType() == Games::FFA){
                if($e->getDamager()->getGame()->getMode() == Modes::FIST){
                    $e->setAttackCooldown(8);
                }elseif($e->getDamager()->getGame()->getMode() == Modes::SUMO){
                    $e->setAttackCooldown(10);
                }
            }
         }

    /**
     * @param EntityDamageByEntityEvent $e
     */
         public function onHit(EntityDamageByEntityEvent $e){
         $d = $e->getDamager();
         $p = $e->getEntity();
         $p->setTagged($d);
         $p->sendMessage("You're now combat logged.");
         $task = $p->setTagged(null);
         $this->getScheduler()->scheduleDelayedTask(new CustomTask($task), 20 * 10);
         }

         public function onQuit(PlayerQuitEvent $e){
         //TODO: Combat Logger, gib kills to who tagged
         }

    /**
     * @param PlayerMoveEvent $e
     */
        public function onMove(PlayerMoveEvent $e)
        {
            if (is_null($e->getPlayer()->getKB())) {
                $e->getPlayer()->sendMessage("Error encountered. ERROR CODE 5: " . Errors::CODE_5);
                $e->setCancelled();
            }
        }

    /**
     * @param Party $party
     * Unsets a party. Used by Party::delete()
     */
        public function deleteParty(Party $party){
        unset($party);
        }


    /**
     * @param Player $owner
     * Creates a party.
     */
    public function createParty(Player $owner)
    {

    $party = new Party($owner);
    $this->parties[] = $party;

    }

    /**
     * @return Party
     * Returns all the current parties.
     */
    public function getParties(): Party{
    return $this->parties;
    }
}