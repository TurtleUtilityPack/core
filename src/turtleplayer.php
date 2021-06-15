<?php

namespace Core;

use Core\Events\TurtleGameEnterEvent;
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

    public $respawning = false;
    public $tag = null;
    public $kb = null;
    public $game = null;

    public function __construct(SourceInterface $interface, $ip, $port)
    {
        parent::__construct($interface, $ip, $port);
        $plugin = $this->getServer()->getPluginManager()->getPlugin("Core");
        if ($plugin instanceof Core) {
            $this->setPlugin($plugin);
        }
    }

    public function getGame(){
    return $this->game;
    }

    public function setGame($game){
    $this->game = $game;
    }

    public function unsetGame(){
    unset($this->game);
    }

    public function initializeGame($game){
    $this->game = $game;
    $event = new TurtleGameEnterEvent($this, $game);
    $event->call();
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
        if($this->game != null) {
            unset($this->game);
        }else{
            $this->sendMessage("Error Encountered. ERROR CODE 10: ".Errors::CODE_10);
        }
        $this->teleport(new Vector3(0, 0, 0, 0, 0, $this->getServer()->getLevelByName("lobby")));
        \Core\Functions\giveItems::giveKit("lobby", $this);
    }

    public function getKB(){
    return $this->kb;
    }

    public function setKB($kb){
    $this->kb = $kb;
    }

    public function setPlugin($plugin){
        $this->plugin=$plugin;
    }

    public function setTagged($tag){
    if($tag !== null) {
        if (is_string($tag)) {
            if (Player::isValidUserName($tag)) {
                $this->tag = $tag;
            } else {
                $this->sendMessage("Error Encountered. ERROR CODE 8: " . Errors::CODE_8);
            }
        } elseif (is_object($tag)) {
            if ($tag instanceof Player) {
                $this->tag = $tag->getName();
            } else {
                $this->sendMessage("Error Encountered. ERROR CODE 6: " . Errors::CODE_6);
            }
        } elseif (!is_object($tag) && !is_string($tag)) {
            $this->sendMessage("Error Encountered. ERROR CODE 7: " . Errors::CODE_7);
        }
    }else{
        $this->tag = null;
    }


       if($tag !== null) {
           if (is_object($tag) && $tag instanceof Player) {
               $players = $this->getServer()->getOnlinePlayers();
               foreach ($players as $player)
                   if ($player->getName() !== $tag->getName()) {
                       $this->hidePlayer($player);
                   }
           } elseif (is_string($tag)) {
               $players = $this->getServer()->getOnlinePlayers();
               foreach ($players as $player)
                   if (Player::isValidUserName($tag)) {
                       if ($player->getName() !== $tag) {
                           $this->hidePlayer($player);
                       }
                   } else {
                       $this->sendMessage("Error Encountered. ERROR CODE 9: " . Errors::CODE_9);
                   }
           }
       }else{
           $players = $this->getServer()->getOnlinePlayers();
           foreach ($players as $player)
           $this->showPlayer($player);
       }
    }

    public function getTagged(){
    return $this->tag;
    }

    public function hasTagged(){
     if($this->tag !== null) {
       return false;
     } else {
    return true;
    }
   }

}