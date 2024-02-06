<?php

namespace Coine\WpHelper\Http\Server;


use Coine\WpHelper\Abstracts\Globals;

class Server extends Globals
{
    protected function getData(): array
    {
        return $_SERVER;
    }
}