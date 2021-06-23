<?php


namespace Core;

use Core\TurtlePlayer;


class Utils{


    public static function getMapNameFormat($player, $type = 'game'){

        if($type == 'game') {
            $name = $player->getName() . "-vs-" . $player->getGame()->getType();
            return $name;
            } elseif ($type == 'opponentName') {

            $players = $player->getGame()->getPlayers();
            foreach($players as $player1) {

                if(!$player1 instanceof $player){
                    $opponent = $player1->getName();
                 }
                }

            return $player->getName()."-vs-".$opponent;

        }

        return 'ok';
    }

}
