<?php

namespace Core\Game;


use libReplay\data\Replay;
use pocketmine\Player;

class Game{

    public $type;
    public $mode;
    public $players = [];
    public $id = null;
    public ?Replay $replay = null;
    public $finished = false;

    public function __construct(array $players, $type, $mode, $id){

    $this->type = $type;
    $this->mode = $mode;
    $this->players = $players;
    $this->id = $id;

    }

    public function getID(){
    return $this->id;
    }

    public function getType(){
    return $this->type;
    }

    public function getMode(){
    return $this->mode;
    }

    public function getPlayers(){
    return $this->players;
    }

    public function setId($id){
    $this->id = $id;
    }

    public function isFinished(){
    if($this->finished){
     return true;
    } else {
     return false;
     }
    }

    public function addPlayer(Player $player){
    $this->players[] = $player;
    }

    public function removePlayer($player)
    {
        if (is_array($player)) {
            foreach ($player as $players) {
                if (($key = array_search($players, $this->players, true)) !== FALSE) {
                    unset($this->players[$key]);
                }
              }
            } else {
                if (($key = array_search($player, $this->players, true)) !== FALSE) {
                    unset($this->players[$key]);
                }
            }
        }

    public function setState($state){
    $this->finished = $state;
    }


}