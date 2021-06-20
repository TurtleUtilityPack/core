<?php

namespace Core\Functions;

use pocketmine\Player;
use Core\Functions\Countdown;
use Core\Core;
use pocketmine\utils\TextFormat;

class RespawnSystem{




    public static function initializeSystem(Player $p, $game){

     foreach(Core::getInstance()->getServer()->getPlayers() as $all){
        $all->sendMessage("");
     }

     $p->setGamemode(3);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(3, "Respawning in...", "3 seconds", $game, $p), 20 * 1);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(2, "Respawning in...", "2 seconds", $game, $p), 20 * 2);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(1, "Respawning in...", "1 seconds", $game, $p), 20 * 3);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(0, "Respawning in...", "0 seconds", $game, $p), 20 * 4);

     $p->sendActionBarMessage(TextFormat::RED . "You have died!");
     //we're gonna pretend the stats api is here :thumbsup:

    }
}