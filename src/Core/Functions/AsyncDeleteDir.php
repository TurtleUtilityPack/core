<?php

namespace Core\Functions;

use Core\Main;
use pocketmine\level\Level;
use pocketmine\scheduler\AsyncTask;

class AsyncDeleteDir extends AsyncTask{

    /**
     * @var string $path
     */
    public string $path;

    /**
     * AsyncDeleteMap constructor.
     * @param string $path
     */
    public function __construct(string $path){
        $this->path = $path;
    }

    public function onRun(): void{

        $path = $this->path;

        $files = glob($path . '/*');
        foreach ($files as $file) {

            $delete = new AsyncDeleteDir($file);
            is_dir($file) ? $delete->run() : unlink($file);

        }

        rmdir($path);
    }
}