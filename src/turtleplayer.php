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
        if($gamemode != "kbffa" or $gamemode != "lobby") {
            $this->gamemode = $gamemode;
        }else{
            $this->gamemode = $gamemode;
            $this->minigame = $gamemode;
        }
    }

    public function getCurrentGamemode(){
        return $this->gamemode;
    }

    public function setCurrentMinigame($gamemode){
        if($gamemode != "kbffa" or $gamemode != "lobby") {
            $this->minigame = $gamemode;
        }else{
            $this->gamemode = $gamemode;
            $this->minigame = $gamemode;
        }
    }

    public function getCurrentMinigame(){
        return $this->minigame;
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

    public function setIsInLobby(){
        $this->setIsRespawning(false);
        $this->setCurrentMinigame("lobby");
        $this->teleport(new Vector3(0, 0, 0, 0, 0, $this->getServer()->getLevelByName("lobby")));
    }

    public function setPlugin($plugin){
        $this->plugin=$plugin;
    }

}