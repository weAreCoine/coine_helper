<?php
declare(strict_types=1);

use Coine\WpHelper\Http\Requests\Get;
use Coine\WpHelper\Http\Requests\Post;
use Coine\WpHelper\Http\Requests\Request;
use Coine\WpHelper\Http\Server\Server;
use Coine\WpHelper\Http\Session\Cookies;
use Coine\WpHelper\Http\Session\Session;
use Coine\WpHelper\Route\Url;

function server(?string $key = null, mixed $default = null): mixed
{
    $instance = Server::getInstance();
    if ($key === null) {
        return $instance;
    }
    return $instance->get($key, $default);
}

function get(?string $key = null, mixed $default = null): mixed
{
    $instance = Get::getInstance();
    if ($key === null) {
        return $instance;
    }
    return $instance->get($key, $default);
}

function post(?string $key = null, mixed $default = null): mixed
{
    $instance = Post::getInstance();
    if ($key === null) {
        return $instance;
    }
    return $instance->get($key, $default);
}

function request(?string $key = null, mixed $default = null): mixed
{
    $instance = Request::getInstance();
    if ($key === null) {
        return $instance;
    }
    return $instance->get($key, $default);
}

function cookie(?string $key = null, mixed $default = null): mixed
{
    $instance = Cookies::getInstance();
    if ($key === null) {
        return $instance;
    }
    return $instance->get($key, $default);
}

function session(?string $key = null, mixed $default = null): mixed
{
    $instance = Session::getInstance();
    if ($key === null) {
        return $instance;
    }
    return $instance->get($key, $default);
}

function url(): Url
{
    return Url::getInstance();
}