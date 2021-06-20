<?php

namespace Core;

class Events
{
    private Main $core;
    public function __construct(Main $core) {
        $this->core = $core;
    }
}