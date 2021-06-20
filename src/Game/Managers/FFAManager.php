<?php

namespace Core\Games;

use Core\Game\Game;
use pocketmine\Player;
use pocketmine\level\level;
use pocketmine\math\Vector3;
use Core\Core;
use Core\Functions\countdown;
use Core\Functions\giveItems;
use Core\Errors;
use Core\Game\Modes;
use Core\Game\Games;

class FFA{

    public Game $sumo_game;
    public Game $fist_game;

    public function __construct(Core $plugin){
        $this->plugin = $plugin;
        $this->sumo_game = Core::getInstance()->getGame('sumo-ffa');
        $this->fist_game = Core::getInstance()->getGame('fist-ffa');
    }

    public function initializeGame(Player $p, $game){
    if($game->getMode() == Games::ACCEPTED_MODES) {
        if ($game->getMode() == Modes::SUMO) {
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(0, "Spawning in...", "0 seconds", $game, $p), 20 * 1);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(5, "Spawning in...", "1 seconds", $game, $p),20 * 2);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(4, "Spawning in...", "2 seconds", $game, $p),20*3);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(3, "Spawning in...", "3 seconds", $game, $p),20*4);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(2, "Spawning in...", "4 seconds", $game, $p),20*5);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(1, "Spawning in...", "5 seconds", $game, $p),20*6);
            giveItems::giveKit(Modes::SUMO, $p);
        }elseif($game->getMode() == Modes::FIST){
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(0, "Spawning in...", "0 seconds", $game, $p), 20 * 1);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(5, "Spawning in...", "1 seconds", $game, $p),20 * 2);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(4, "Spawning in...", "2 seconds", $game, $p),20*3);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(3, "Spawning in...", "3 seconds", $game, $p),20*4);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(2, "Spawning in...", "4 seconds", $game, $p),20*5);
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new countdown(1, "Spawning in...", "5 seconds", $game, $p),20*6);
            giveItems::giveKit(Modes::FIST, $p);
        }
      }
    }

    public function getGame(string $name){
        if ($name == 'sumo' or $name == 'fist') {
            if ($name == 'sumo') {
                return $this->sumo_game;
            } elseif ($name == 'fist') {
                return $this->fist_game;
            }
        }
    }

}