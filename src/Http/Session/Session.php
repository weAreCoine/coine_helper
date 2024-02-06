<?php

namespace Coine\WpHelper\Http\Session;


use Coine\WpHelper\Abstracts\Globals;

class Session extends Globals
{
    protected function getData(): array
    {
        return $_SESSION;
    }
}