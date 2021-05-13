<?php

use pocketmine\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\level\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\network\protocol\NetworkSession;
use pocketmine\math\Vector3;
use pocketmine\network\SourceInterface;
use Core\Core;


class TurtlePlayer extends Player{

    public $gamemode = "lobby";
    public $minigame = "lobby";

    public function __construct(SourceInterface $interface, $clientID, $ip, $port)
    {
        parent::__construct($interface, $clientID, $ip, $port);
        $plugin = $this->getServer()->getPluginManager()->getPlugin("Core");
        if ($plugin instanceof Main) {
            $this->setPlugin($plugin);
        }
    }

    public function setCurrentGamemode($gamemode){
        $this->gamemode = $gamemode;
    }

    public function getCurrentGamemode(){
        return $this->gamemode;
    }

    public function setCurrentMinigame($gamemode){
        $this->minigame = $gamemode;
    }

    public function getCurrentMinigame(){
        return $this->minigame;
    }

    public function setPlugin($plugin){
        $this->plugin=$plugin;
    }

}