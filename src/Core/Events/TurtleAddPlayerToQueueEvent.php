<?php

namespace Core\Events;

use Core\Game\Game;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\player;
use pocketmine\plugin\Plugin;

class TurtleAddPlayerToQueueEvent extends PluginEvent{

    /**
     * @var Player
     */
    public Player $player;

    public function __construct($player)
    {
        parent::__construct(self::getPlugin());
        $this->player = $player;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }


}