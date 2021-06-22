<?php

namespace Core\Games;

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

class Duels {

    public static function initializeGame($player, Game $game){


        $level = Main::getInstance()->createMap($player, Main::getInstance()->getRandomMap());

        if($game->getType() == GamesManager::BOT) {
            $nbt = Entity::createBaseNBT($player->asVector3()->subtract(10, 0, 10));
            $nbt->setTag($player->namedtag->getTag("Skin"));
            $bot = new Bot($level, $nbt, $player->getName(), 'nodebuff');
            $bot->setNameTagAlwaysVisible(true);
            $bot->spawnToAll();
            $bot->setCanSaveWithChunk(false);

            GiveItems::giveKit('nodebuff', $bot);
            GiveItems::giveKit('nodebuff', $player);
        }
    }

}