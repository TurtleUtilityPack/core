<?php

namespace Core\Functions;

use pocketmine\Player;
use pocketmine\level\level;
use pocketmine\math\Vector3;
use Core\Core;
use pocketmine\scheduler\Task;
use Core\Game\Modes;
use Core\Game\Games;

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
                    if($this->player->getCurrentMinigame() != "lobby") {
                        $this->player->addTitle($this->text, $this->text2, 20, 60, 40);
                        $this->player->getInventory()->clearAll();
                    }
                }
            }
        }else {
            if(!$this->player == null) {
                if($this->player->isOnline()) {
                    $this->player->setGamemode(0);
                    if($this->player->getCurrentMinigame() == Games::FFA) {
                        if ($this->mode == Modes::SUMO) {
                            $this->player->teleport(new Vector3(1, 1, 1, 0, 0, Core::getInstance()->getServer()->getLevelByName("sumoFFA")));
                            $this->player->setGamemode(0);
                            $this->player->setIsRespawning(false);
                        } elseif ($this->mode == Modes::FIST) {
                            $this->player->teleport(new Vector3(1, 1, 1, 0, 0, Core::getInstance()->getServer()->getLevelByName("fistFFA")));
                            $this->player->setGamemode(0);
                            $this->player->setIsRespawning(false);
                        }
                    }elseif($this->player->getCurrentMinigame() == "KBFFA" or $this->player->getCurrentGamemode() == "KBFFA"){
                        $this->player->teleport(new Vector3(1, 1, 1, 0, 0, Core::getInstance()->getServer()->getLevelByName("kbFFA")));
                        $this->player->setGamemode(0);
                        $this->player->setIsRespawning(false);
                    }
                }
            }
        }
    }
}