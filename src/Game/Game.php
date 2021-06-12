<?php

namespace Core\Game;


use libReplay\data\Replay;

class Game{

    public $type;
    public $mode;
    public $players = [];
    public $id = null;
    public ?Replay $replay = null;
    public $finished = false;

    public function __construct(array $players, $type, $mode){

    $this->type = $type;
    $this->mode = $mode;
    $this->players = $players;

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

    public function setState($state){
    $this->finished = $state;
    }


}