<?php

namespace Core\Game;


use Core\Utils;
use libReplay\data\Replay;
use pocketmine\Player;

class Game{

    /**@var string*/
    public string $type;

    /**@var string*/
    public string $mode;

    /**@var array|Player*/
    public $players = [];

    /**@var string*/
    public string $id;

    /**@var Replay*/
    public Replay $replay;

    /**@var bool*/
    public bool $finished = false;

    /**
     * @var null|string
     */
    public string|null $difficulty = null;


    /**
     * Game constructor.
     * @param array|null $players
     * @param $type
     * @param $mode
     * @param null|string $id
     * @param null|string $difficulty
     */
    public function __construct(array|null $players, $type, $mode, $id = null, $difficulty = null){

    $this->type = $type;
    $this->mode = $mode;
    $this->players = $players;

    if($difficulty != null){
        $this->difficulty = $difficulty;
    }

    if(is_null($id)) {
        $this->setId();
    } else {
        $this->id = $id;
    }

    }

    /**
     * @return string
     * Gives game ID
     */
    public function getID(): string{
    return $this->id;
    }

    /**
     * @return string
     * Gives game Type
     * For Example: FFA
     */
    public function getType(): string{
    return $this->type;
    }

    /**
     * @return string
     * Gives game-mode
     * For Example: Fist
     */
    public function getMode(): string{
    return $this->mode;
    }

    /**
     * @return array|Player
     * Gives current players in game.
     */
    public function getPlayers(): array{
    return $this->players;
    }

    /**
     * Sets Game ID
     */
    public function setId(){

        if(!is_null($this->getPlayers())) {
            $players = $this->getPlayers();
            $this->id = $players[0]->getName() . "-vs-" . $this->getType();
        }

    }

    /**
     * @return bool
     * Checks if game is finished, returns true for isFinished, false for not finished.
     */
    public function isFinished(): bool{
    if($this->finished){
     return true;
    } else {
     return false;
     }
    }

    /**
     * @param Player $player
     * Adds a player to the game.
     */
    public function addPlayer(Player $player){
    $this->players[] = $player;
    }

    /**
     * @param Player|array $player
     * Removes a player from the game.
     */
    public function removePlayer(Player $player)
    {
        if (is_array($player)) {
            foreach ($player as $players) {
                if (($key = array_search($players, $this->players, true)) !== FALSE) {
                    unset($this->players[$key]);
                }
              }
            } else {
                if (($key = array_search($player, $this->players, true)) !== FALSE) {
                    unset($this->players[$key]);
                }
            }
        }

    /**
     * @param bool $state
     * Sets if the game is finished.
     */
    public function setState(bool $state){
    $this->finished = $state;
    }

    /**
     * get bot difficulty
     * @return string
     */
    public function getDifficulty(): string{
        return $this->difficulty;
    }


}