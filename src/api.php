<?php

namespace gamemode;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\Listener;
use gamemode\TurtlePlayer;
class api extends PluginBase implements Listener{

    public function onEnable():void{
    }

    public function onCreate(PlayerCreationEvent $e){
        $e->setPlayerClass(TurtlePlayer::class);
    }

}
