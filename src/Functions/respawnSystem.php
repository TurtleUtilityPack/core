<?php

namespace Core\Functions;

use pocketmine\Player;
use Core\Functions\countdown;
use Core\Core;

class respawnSystem{




    public static function initializeSystem($p, $game){

     foreach(Core::getInstance()->getServer()->getPlayers() as $all){
     $all->sendMessage("");
     }

     $p->setGamemode(3);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(3, "Respawning in...", "3 seconds", $game, $p), 20 * 1);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(2, "Respawning in...", "2 seconds", $game, $p), 20 * 2);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(1, "Respawning in...", "1 seconds", $game, $p), 20 * 3);
     Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(0, "Respawning in...", "0 seconds", $game, $p), 20 * 4);
     $p->sendMessage("You died! To go back to hub type 'lobby' before you get respawned. If you failed to do so, please go to hub manually using /hub.");
     //we're gonna pretend the stats api is here :thumbsup:

    }
}