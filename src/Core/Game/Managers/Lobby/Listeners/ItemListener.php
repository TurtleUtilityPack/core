<?php


namespace Core\Game\Managers\Lobby\Listeners;


use Core\Events\TurtleGameEnterEvent;
use Core\Game\GamesManager;
use Core\Game\FFA;
use Core\Main;
use Core\TurtlePlayer;
use Core\Entities\Bot;
use Core\Utils;
use pocketmine\entity\Entity;
use Core\Forms\{SimpleForm, ModalForm, CustomForm};
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ItemListener implements Listener
{

    public function onInteract(PlayerInteractEvent $event) {

        $item = $event->getItem();
        if(!$event->getPlayer()->isOp()) {
            $event->setCancelled();
        }
        if(!$event->getPlayer()->hasPermission("events.turtleclient.bypassinteract")) {
            $event->setCancelled();
        }
        $player = $event->getPlayer();

        if($item->getCustomName() === TextFormat::BOLD . TextFormat::BLUE . "Navigator") {

            $form = new SimpleForm(function (Player $player, $data) {

                if (!is_null($data)) {

                    switch($data){

                        case "bot":


                            $form1 = new SimpleForm(function (Player $player, $data){

                                if(!is_null($data)) {

                                    switch($data){

                                        case "easy":


                                            $nbt = Entity::createBaseNBT($player->asVector3()->subtract(10, 0, 10));
                                            $nbt->setTag($player->namedtag->getTag("Skin"));

                                            $bot = new Bot(Main::getInstance()->getServer()->getLevelByName("lobby"), $nbt, $player->getName(), 'nodebuff', GamesManager::DIFFICULTY_EASY);

                                            $bot->setNameTagAlwaysVisible(true);
                                            $bot->spawnTo($player);
                                            $bot->setCanSaveWithChunk(false);
                                            $bot->setImmobile(true);

                                            $game = Main::getInstance()->createGame([$player, $bot], GamesManager::BOT, GamesManager::DIFFICULTY_EASY);

                                            $player->setGame($game);

                                            Utils::createMap(Utils::getMapNameFormat($player), Utils::getRandomMap());
                                            Main::getInstance()->getServer()->loadLevel(Utils::getMapNameFormat($player));

                                            $level = Main::getInstance()->getServer()->getLevelByName(Utils::getMapNameFormat($player));

                                            $event = new TurtleGameEnterEvent($player, $game, $ok = $level);
                                            $event->call();

                                            break;

                                        case "normal":

                                            $nbt = Entity::createBaseNBT($player->asVector3()->subtract(10, 0, 10));
                                            $nbt->setTag($player->namedtag->getTag("Skin"));

                                            $bot = new Bot(Main::getInstance()->getServer()->getLevelByName("lobby"), $nbt, $player->getName(), 'nodebuff', GamesManager::DIFFICULTY_MEDIUM);
                                            $bot->setNameTagAlwaysVisible(true);
                                            $bot->spawnTo($player);
                                            $bot->setCanSaveWithChunk(false);
                                            $bot->setImmobile(true);


                                            $game = Main::getInstance()->createGame([$player, $bot], GamesManager::BOT, GamesManager::DIFFICULTY_MEDIUM);

                                            $player->setGame($game);


                                            Utils::createMap(Utils::getMapNameFormat($player), Utils::getRandomMap());
                                            Main::getInstance()->getServer()->loadLevel(Utils::getMapNameFormat($player));

                                            $level = Main::getInstance()->getServer()->getLevelByName(Utils::getMapNameFormat($player));


                                            $event = new TurtleGameEnterEvent($player, $game, $ok = $level);
                                            $event->call();

                                            break;

                                        case "hard":

                                            $nbt = Entity::createBaseNBT($player->asVector3()->subtract(10, 0, 10));
                                            $nbt->setTag($player->namedtag->getTag("Skin"));

                                            $bot = new Bot(Main::getInstance()->getServer()->getLevelByName("lobby"), $nbt, $player->getName(), 'nodebuff', GamesManager::DIFFICULTY_HARD);
                                            $bot->setNameTagAlwaysVisible(true);
                                            $bot->spawnTo($player);
                                            $bot->setCanSaveWithChunk(false);
                                            $bot->setImmobile(true);


                                            $game = Main::getInstance()->createGame([$player, $bot], GamesManager::BOT, GamesManager::DIFFICULTY_HARD);

                                            $player->setGame($game);


                                            Utils::createMap(Utils::getMapNameFormat($player), Utils::getRandomMap());
                                            Main::getInstance()->getServer()->loadLevel(Utils::getMapNameFormat($player));

                                            $level = Main::getInstance()->getServer()->getLevelByName(Utils::getMapNameFormat($player));


                                            $event = new TurtleGameEnterEvent($player, $game, $ok = $level);
                                            $event->call();

                                            break;
                                    }

                                }

                            });

                            $form1->setTitle("Bots");
                            $form1->addButton("Easy", -1, $imagePath = "", "easy");
                            $form1->addButton("Medium", -1, $imagePath = "", "medium");
                            $form1->addButton("Hard", -1, $imagePath = "", "hard");

                            $form1->sendToPlayer($player);

                            break;

                        case "ffa":

                            $form2 = new SimpleForm(function (Player $player, $data){

                                switch ($data) {

                                    case "Sumo":

                                        if($player instanceof TurtlePlayer){
                                            if($player->partyExists()){
                                                foreach($player->getParty()->getPlayers() as $players){
                                                    $players->initializeGame(Main::getInstance()->getGame('sumo-ffa'));
                                                }
                                            }else{
                                                $player->initializeGame(Main::getInstance()->getGame('sumo-ffa'));
                                            }
                                        }
                                        break;

                                    case "Fist":

                                        if($player instanceof TurtlePlayer){
                                            if($player->partyExists()){
                                                foreach($player->getParty()->getPlayers() as $players){
                                                    $players->initializeGame(Main::getInstance()->getGame('fist-ffa'));
                                                }
                                            }else{
                                                $player->initializeGame(Main::getInstance()->getGame('fist-ffa'));
                                            }
                                        }
                                        break;

                                }
                            });

                            $form2->setTitle("FFA");
                            $form2->addButton("Sumo", -1, "", "Sumo");
                            $form2->addButton("Fist", -1, "", "Fist");

                            $form2->sendToPlayer($player);

                            break;
                    }

                }

            });


            $form->setTitle("§e§lNavigator");
            $form->addButton("FFA", -1, "", "ffa");
            $form->addButton("Bots", -1, "", "bot");

            $form->sendToPlayer($player);
        }

    }



}