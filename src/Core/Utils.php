<?php


namespace Core;

use Core\Functions\AsyncDeleteDir;
use Core\Functions\AsyncDeleteMap;
use Core\TurtlePlayer;


class Utils{


    /**
     * @param $player
     * @param string $type
     * @return string
     * get map name format for AsyncCreateMap
     */
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

    /**
     * @return mixed
     * @throws \Exception
     * Gets random map name format
     */
    public static function getRandomMap(){

        $folders = Main::getInstance()->getConfig();
        $count = count($folders->getAll());
        $int = random_int(0, $count);

        $level = $folders->get($int);

        return $level;

    }

    /**
     * @param $player
     * @param $player2
     * @return string
     */
    public static function buildID($player, $player2){

        return $player->getName()."-vs-".$player2->getName();

    }

    /**
     * @return string
     */
    public static function getDataPath(): string{

        return Main::getInstance()->getServer()->getDataPath();

    }

    /**
     * @param $id
     * @param $folderName
     */
    public static function deleteMap($id, $folderName){

        $dataPath = Main::getInstance()->getServer()->getDataPath();
        $player = $id;

        $mapName = $player;

        if (!Utils::isLevelGenerated($mapName)) {

            return;
        }

        if (!Utils::isLevelLoaded($mapName)) {

            return;
        }

        Utils::unloadLevel($mapName);

        $folderName = $dataPath . "worlds" . DIRECTORY_SEPARATOR . $mapName;

        Utils::removeDirectory($folderName);

    }

    /**
     * @param string $levelName
     * @return bool|null
     */
    public static function isLevelGenerated(string $levelName){

        return Main::getInstance()->getServer()->isLevelGenerated($levelName);

    }

    /**
     * @param string $levelName
     * @return bool|null
     */
    public static function isLevelLoaded(string $levelName){

        return Main::getInstance()->getServer()->isLevelLoaded($levelName);

    }

    /**
     * @param string $levelName
     */
    public static function unloadLevel(string $levelName){

        Main::getInstance()->getServer()->unloadLevel(Main::getInstance()->getServer()->getLevelByName($levelName));

    }

    /**
     * @param string $player
     * @param $folderName
     */
    public static function createMap(string $player, $folderName)
    {
        $mapname = $player;

        $zipPath = Main::getInstance()->getDataFolder() . "arenas/" . $folderName . ".zip";

        if (file_exists(Main::getInstance()->getServer()->getDataPath() . "worlds/". $mapname)) {
            Utils::deleteMap($player, $folderName);
        }

        $zipArchive = new \ZipArchive();
        if ($zipArchive->open($zipPath, \ZipArchive::CREATE) == true) {
            $zipArchive->extractTo(Main::getInstance()->getServer()->getDataPath() . "worlds/");
            $zipArchive->close();
        }

        rename(Main::getInstance()->getServer()->getDataPath() . "worlds/$folderName", Main::getInstance()->getServer()->getDataPath() . "worlds" . DIRECTORY_SEPARATOR . $mapname);


    }


    /**
     * @param $folderName
     */
    public static function removeDirectory($folderName){

        $delete = new AsyncDeleteDir($folderName);
        $delete->run();

    }

}
