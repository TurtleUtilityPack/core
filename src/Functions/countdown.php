<?php

namespace Core\Functions;

use pocketmine\Player;
use pocketmine\level\level;
use pocketmine\math\Vector3;
use Core\Core;
use pocketmine\scheduler\Task;

class countdown extends Task
{

    public function __construct($num, $string, $string2, $mode, $player)
    {
        $this->num = $num;
        $this->player = $player;
        $this->text = $string;
        $this->text2 = $string2;
        $this->mode = $mode;
    }

    public function onRun(int $tick){
        if (!$this->num == 0){
            if(!$this->player == null) {
                if($this->player->isOnline()) {
                    $this->player->addTitle($this->text, $this->text2, 20, 60, 40);
                    $this->player->getInventory()->clearAll();
                }
            }
        }else {
            if(!$this->player == null) {
                if($this->player->isOnline()) {
                    $this->player->setGamemode(0);
                    if($this->player->getMinigame() == "FFA") {
                        if ($this->mode == "sumo") {
                            $this->player->teleport(new Vector3(244, 88, 182, 0, 0, Core::getInstance()->getServer()->getLevelByName("sumoFFA")));
                        } elseif ($this->mode == "fist") {
                            $this->player->teleport(new Vector3(244, 88, 182, 0, 0, Core::getInstance()->getServer()->getLevelByName("fistFFA")));
                        }
                    }
                }
            }
        }
    }
}