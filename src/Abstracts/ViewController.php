<?php
declare(strict_types=1);

namespace Coine\WpHelper\Abstracts;


use Coine\WpHelper\Traits\Singleton;

abstract class ViewController
{
    protected array $items;
    protected string $path;
    protected string $basePath = '';
    protected bool $json = false;

    use Singleton;

    public function __construct()
    {
        $this->path = COINE_DIR;
        $this->items = [];

        return $this;
    }

    public function with(...$items): static
    {
        $this->items = $items;

        return $this;
    }

    public function route(string $path): static
    {
        $path = str_starts_with($path, '/') ? $this->basePath . $path : "$this->basePath/$path";
        $path = !str_ends_with($path, '.php') ? "$path.php" : $path;
        $this->path = COINE_DIR . $path;

        return $this;
    }

    public function toJson(bool $toJson = true): static
    {
        $this->json = $toJson;
        return $this;
    }

    public function bufferedRequire(bool $once = true): string
    {
        ob_start();

        $this->require($once);

        return ob_get_clean();
    }

    public function require(bool $once = true): static
    {
        if (!empty($this->path)) {

            if ($this->json) {
                $this->items = [['json' => json_encode($this->items[0])]];
            }
            foreach ($this->items as $item) {
                extract($item);
            }

            if ($once) {
                require_once $this->path;
            } else {
                require $this->path;
            }
            $this->clear();
        }

        return $this;
    }

    public function clear(bool $all = false)
    {
        $this->path = COINE_DIR;
        $this->items = [];

        if ($all) {
            $this->basePath = '';
        }

    }

    public function bufferedInclude(bool $once = true): string
    {
        ob_start();
        $this->include($once);

        return ob_get_clean();
    }

    public function include(bool $once = true): void
    {
        if (!empty($this->path)) {

            if ($this->json) {
                $this->items = [['json' => json_encode($this->items[0])]];
            }

            foreach ($this->items as $item) {
                extract($item);
            }

            if ($once) {
                include_once $this->path;
            } else {
                include $this->path;
            }
            $this->clear();
        }
    }

    public function setBasePath(string $basePath): static
    {

        $this->basePath = str_starts_with($basePath, '/') ? $basePath : "/$basePath";
        $this->basePath = str_starts_with($this->basePath, '/Views') ? $this->basePath : "/Views/$this->basePath";
        $this->basePath = str_ends_with($this->basePath, '/') ? substr($this->basePath, 0, -1) : $this->basePath;

        return $this;
    }

}