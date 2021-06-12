<?php

namespace Core\Game;


class Game{

    public $type;
    public $mode;
    public $players = [];
    public $replay;
    public $finished = false;

    public function __construct(array $players, $type, $mode){

    $this->type = $type;
    $this->mode = $mode;
    $this->players = $players;

    }


}