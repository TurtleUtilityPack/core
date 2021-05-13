<?php

namespace Core\Functions;


use pocketmine\Player;

class giveItems{
    public function __construct(Core $plugin){
        $this->plugin = $plugin;
    }

    public function giveKit($kit, $p){
        if($kit == "fist"){
            $p->getInventory()->setItem(7, Item::get(364, 0, 64));
        }elseif($kit == "sumo"){
            $p->getInventory()->setItem(7, Item::get(364, 0, 64));
        }
    }
}