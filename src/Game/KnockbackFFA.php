<?php

namespace Core\Games;

use Core\Core;
use pocketmine\Player;
use pocketmine\level\level;
use Core\Functions\giveItems;

class KnockbackFFA{

    public function __construct(Core $plugin){
        $this->plugin = $plugin;
    }

    public function initializeGame(Player $p, $game)
    {
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(0, "Spawning in...", "0 seconds", $game, $p), 20 * 1);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(5, "Spawning in...", "1 seconds", $game, $p), 20 * 2);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(4, "Spawning in...", "2 seconds", $game, $p), 20 * 3);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(3, "Spawning in...", "3 seconds", $game, $p), 20 * 4);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(2, "Spawning in...", "4 seconds", $game, $p), 20 * 5);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(1, "Spawning in...", "5 seconds", $game, $p), 20 * 6);
            giveItems::giveKit(Modes::SUMO, $p);
    }

}