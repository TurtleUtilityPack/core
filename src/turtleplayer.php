<?php

use pocketmine\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use gamemode\api;


class TurtlePlayer extends Player{

    public $gamemode = "lobby";

    public function __construct(Server $server, NetworkSession $session, PlayerInfo $playerInfo, bool $authenticated, Location $spawnLocation, ?CompoundTag $namedtag)
    {
        parent::__construct($server, $session, $playerInfo, $authenticated, $spawnLocation, $namedtag);
        $plugin = $this->getServer()->getPluginManager()->getPlugin("Gamemode API");
        if ($plugin instanceof api) {
            $this->setPlugin($plugin);
        }
    }

    public function setCurrentGamemode($gamemode){
        $this->gamemode = $gamemode;
    }

    public function getCurrentGamemode(){
        return $this->gamemode;
    }

    public function setPlugin($plugin){
        $this->plugin=$plugin;
    }

}