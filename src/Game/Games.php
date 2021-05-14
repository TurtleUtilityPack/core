<?php

namespace Core\Game;

class Games{

    const ACCEPTED_MODES = ["FFA", "KBFFA"];

    const FFA = "FFA";
    const KBFFA = "KBFFA";

    public function validate($game){
        if($game == $this::ACCEPTED_MODES) {
            return true;
        }else{
            return false;
        }
    }

}

