<?php
declare(strict_types=1);
namespace Coine\WpHelper\Http\Requests;
use Coine\WpHelper\Abstracts\Globals;

class Post extends Globals{
    protected function getData(): array
    {
        return $_POST;
    }
}