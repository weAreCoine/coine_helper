<?php
declare(strict_types=1);

use Coine\WpHelper\Http\Server\Server;

function server(?string $key = null, mixed $default = null): mixed
{
    if ($key === null) {
        return Server::getInstance();
    }
    return Server::getInstance()->get($key, $default);
}