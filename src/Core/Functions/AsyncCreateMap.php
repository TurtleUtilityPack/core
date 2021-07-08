<?php

namespace Core\Functions;

use Core\Utils;
use Core\Main;
use pocketmine\level\Level;
use pocketmine\scheduler\AsyncTask;

class AsyncCreateMap extends AsyncTask{

    /**
     * @var string
     */
    public string $id;

    /**
     * @var string $folderName
     */
    public string $folderName;

    /**
     * @var Level $level
     */
    public Level $level;


    /** @var string */
    public string $dataPath;

    /**
     * AsyncDeleteMap constructor.
     * @param string $id
     * @param $folderName
     */
    public function __construct($id, $folderName){

        $this->id = $id;
        $this->folderName = $folderName;
        $this->dataPath = Utils::getDataPath();
    }

    public function onRun(){

        $folderName = $this->folderName;
        $player = $this->id;


        $mapname = $player;

        $zipPath = $this->dataPath . "plugin_data/Core/arenas" . $folderName . ".zip";

        if (file_exists($this->dataPath . "worlds" . DIRECTORY_SEPARATOR . $mapname)) {
            Utils::deleteMap($player, $folderName);
        }

        $zipArchive = new \ZipArchive();
        if ($zipArchive->open($zipPath) == true) {
            $zipArchive->extractTo($this->dataPath . "worlds");
            $zipArchive->close();
        }

        rename($this->dataPath . "worlds" . DIRECTORY_SEPARATOR . $folderName, $this->dataPath . "worlds" . DIRECTORY_SEPARATOR . $mapname);
    }
}