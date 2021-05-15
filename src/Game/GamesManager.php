<?php

namespace Core\Game;

use Core\Games\FFA;


class GamesManager{

    const ACCEPTED_MODES = ["FFA", "KBFFA"];
    const SINGLE_MODES = ["lobby", "KBFFA"];

    const FFA = "FFA";
    const KBFFA = "KBFFA";

    public function validate($game){
        if($game == $this::ACCEPTED_MODES) {
            return true;
        } else {
            return false;
        }
    }

    public function getFFAManager(){
        return FFA::class;
    }


}

class Games{

    const ACCEPTED_MODES = ["FFA", "KBFFA"];
    const SINGLE_MODES = ["lobby", "KBFFA"];

    const FFA = "FFA";
    const KBFFA = "KBFFA";

    public function validate($game){
        if($game == $this::ACCEPTED_MODES) {
            return true;
        } else {
            return false;
        }
    }

    public function getFFAManager(){
        return FFA::class;
    }


}



