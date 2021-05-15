<?php

use pocketmine\Player;
use pocketmine\level\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\math\Vector3;
use pocketmine\network\SourceInterface;
use Core\Core;
use Core\Functions\respawnSystem;
use Core\Games\FFA;
use Core\Errors;
use Core\Game\Modes;
use Core\Game\Games;
use Core\Games\KnockbackFFA;


class TurtlePlayer extends Player{

    public $gamemode = "lobby";
    public $minigame = "lobby";
    public $respawning = false;

    public function __construct(SourceInterface $interface, $ip, $port)
    {
        parent::__construct($interface, $ip, $port);
        $plugin = $this->getServer()->getPluginManager()->getPlugin("Core");
        if ($plugin instanceof Main) {
            $this->setPlugin($plugin);
        }
    }

    public function setCurrentGamemode($gamemode){
        if($gamemode != "KBFFA" or $gamemode != "lobby") {
            if(Core::getInstance()->getModesManager()->validate($gamemode) == true) {
                $this->gamemode = $gamemode;
            }else{
                $this->sendMessage("Error encountered. ERROR CODE 1: ".Errors::CODE_1);
            }
        }else{
            $this->gamemode = $gamemode;
            $this->minigame = $gamemode;
        }
    }

    public function getCurrentGamemode(){
        return $this->gamemode;
    }

    public function setCurrentMinigame($gamemode){
        if($gamemode != "KBFFA" or $gamemode != "lobby") {
            if(Core::getInstance()->getGamesManager()->validate($gamemode) == true) {
                $this->minigame = $gamemode;
            }else{
                $this->sendMessage("Error encountered. ERROR CODE 2: ".Errors::CODE_2);
            }
        }else{
            $this->gamemode = $gamemode;
            $this->minigame = $gamemode;
        }
    }

    public function getCurrentMinigame(){
        return $this->minigame;
    }

    public function initializeGame($minigame, $mode){
    $this->setCurrentMinigame($minigame);
    $this->setCurrentGamemode($mode);
    if($minigame == Core::getInstance()->getGamesManager()::FFA) {
        if (Core::getInstance()->getGamesManager()->validate($mode) == true && Core::getInstance()->getGames()->validate($minigame) == true) {
            Core::getInstance()->getGamesManager()->getFFAManager()->initializeGame($this, $mode);
        } else {
            $this->sendMessage("Error encountered. ERROR CODE 3: " . Errors::CODE_3);
        }
      }elseif($minigame == Core::getInstance()->getGamesManager()::KBFFA){
        Core::getInstance()->getGamesManager()->getKBFFAManager()->initializeGame($this, $mode);
    }
    }

    public function initializeRespawn($game){
     respawnSystem::initializeSystem($this, $game);
    }

    public function setIsRespawning(bool $bool){
        $this->respawning = $bool;
    }

    public function getIsRespawning(){
        return $this->respawning;
    }

    public function initializeLobby(){
        $this->setIsRespawning(false);
        $this->setCurrentMinigame("lobby");
        $this->teleport(new Vector3(0, 0, 0, 0, 0, $this->getServer()->getLevelByName("lobby")));
    }

    public function setPlugin($plugin){
        $this->plugin=$plugin;
    }

}