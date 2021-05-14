<?php

namespace Core\Game;

class Modes{

    const ACCEPTED_MODES = ["FFA_FIST", "FIST_SUMO"];

    const SUMO = "FFA_SUMO";
    const FIST = "FFA_FIST";

    public function validate($game){
        if($game == $this::ACCEPTED_MODES) {
           return true;
        }else{
           return false;
        }


    }
}

