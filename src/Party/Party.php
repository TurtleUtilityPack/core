<?php

use pocketmine\Player;

class Party{

    /**
     * @var Player|array
     * Players in the party.
     */
    public $players = [];

    /**
     * @var Player
     * Party's owner.
     */
    public Player $owner;



}