<?php

namespace Core\Events;

use Core\Game\Game;
use Core\Game\Queue;
use Core\Main;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\player;
use pocketmine\plugin\Plugin;

class TurtleAddPlayerToQueueEvent extends PluginEvent{

    /**
     * @var Player
     */
    public Player $player;

    /**
     * @var Queue
     */
    public Queue $queue;

    public function __construct($player, Queue $queue)
    {
        parent::__construct(Main::getInstance()->getServer()->getPluginManager()->getPlugin("Core"));
        $this->player = $player;
        $this->queue = $queue;

    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Queue
     */
    public function getQueue(): Queue
    {
        return $this->queue;
    }


}