<?php

namespace Core\Events;

use Core\Game\Game;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\player;

class TurtleGameEndEvent extends PluginEvent{

    public $players;
    public $winner;
    public $looser;

    public function __construct(array $players, Player $winner, Player $looser){
     parent::__construct(self::getPlugin());
     $this->players = $players;
     $this->winner = $winner;
     $this->looser = $looser;
    }

    public function getGamePlayers(){
    return $this->players;
    }

    public function getWinner(){
    return $this->winner;
    }

    public function getLooser(){
    return $this->looser;
    }

}