<?php

namespace Core\Functions;

use Core\Main;
use Core\Utils;
use pocketmine\level\Level;
use pocketmine\scheduler\AsyncTask;

class AsyncDeleteMap extends AsyncTask{

    /**
     * @var string
     */
    public string $id;

    /**
     * @var string $folderName
     */
    public string $folderName;

    /**
     * @var Main
     */
    public Main $plugin;

    /**
     * @var string
     */
    public string $dataPath;


    /**
     * AsyncDeleteMap constructor.
     * @param string $id
     * @param $folderName
     */
    public function __construct(string $id, $folderName){

        $this->player = $id;
        $this->folderName = $folderName;

        $this->dataPath = Utils::getDataPath();

    }

    public function onRun(): void{

        $player = $this->id;

        $mapName = $player;

        if (!Utils::isLevelGenerated($mapName)) {

            return;
        }

        if (!Utils::isLevelLoaded($mapName)) {

            return;
        }

        Utils::unloadLevel($mapName);

        $folderName = $this->dataPath . "worlds" . DIRECTORY_SEPARATOR . $mapName;

        Utils::removeDirectory($folderName);
    }
}