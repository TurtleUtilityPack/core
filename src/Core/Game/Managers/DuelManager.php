<?php

namespace Core\Games;

use Core\Functions\Countdown;
use Core\Functions\GiveItems;
use Core\Game\Game;
use Core\Game\GamesManager;
use Core\Main;
use Core\TurtlePlayer;
use ethaniccc\NoDebuffBot\Bot;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\network\mcpe\protocol\types\GameMode;

class Duels {

    public static function initializeGame($player, Game $game){


        $level = Main::getInstance()->createMap($player, Main::getInstance()->getRandomMap());

        if($game->getType() == GamesManager::BOT) {

            $nbt = Entity::createBaseNBT($player->asVector3()->subtract(10, 0, 10));
            $nbt->setTag($player->namedtag->getTag("Skin"));
            $bot = new Bot($level, $nbt, $player->getName(), 'nodebuff', $game->getDifficulty());
            $bot->setNameTagAlwaysVisible(true);
            $bot->spawnToAll();
            $bot->setCanSaveWithChunk(false);
            $bot->teleport($level->getSafeSpawn());
            $bot->setImmobile(true);

            GiveItems::giveKit('nodebuff', $bot);
            GiveItems::giveKit('nodebuff', $player);

            $p = $player;

            $p->setGamemode(GameMode::SURVIVAL_VIEWER);
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(3, "Spawning in...", "3 seconds", $game, $p, true), 20 * 1);
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(2, "Spawning in...", "2 seconds", $game, $p, true), 20 * 2);
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(1, "Spawning in...", "1 seconds", $game, $p, true), 20 * 3);
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new Countdown(0, "Spawning in...", "0 seconds", $game, $p, true), 20 * 4);
        }
    }

}