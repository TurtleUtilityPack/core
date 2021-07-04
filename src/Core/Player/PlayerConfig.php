<?php

namespace Core\Player;

class PlayerConfig{

    /**
     * @var string
     */
    public string $deviceQueuing;

    /**
     * @var string
     */
    public string $javaInventory;

    /**
     * @var array|string[]
     */
    public array $configs = ['deviceQueuing', 'javaInventory'];

    public function __construct($d, $j){
        $this->deviceQueuing = $d;
        $this->javaInventory = $j;
    }


}