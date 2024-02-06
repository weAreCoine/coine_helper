<?php
declare(strict_types=1);

namespace Coine\WpHelper\Route;

use Coine\WpHelper\Traits\Singleton;
use JetBrains\PhpStorm\Pure;

class Url
{
    use Singleton;

    public function createFromCurrent(array ...$getParameters): string
    {
        $current = $this->current();
        foreach ($getParameters as $getParameter) {
            $current .= "&$getParameter[0]=$getParameter[1]";
        }

        return preg_replace('/&/', '?', $current, 1);
    }

    public function current(bool $includeGetParameters = false): string
    {
        return $this->site() . ($includeGetParameters ? $_SERVER['REQUEST_URI'] : $this->purgeGetParameters($_SERVER['REQUEST_URI']));
    }

    public function site()
    {
        //TODO Rimuovi dipendenza da Wordpress
        return function_exists('site_url') ? site_url() : '';
    }

    protected function purgeGetParameters(string $path): string
    {
        $getPosition = strpos($path, "?") ?: strlen($path);

        return substr($path, 0, $getPosition);
    }

    public function isFrontend(): bool
    {
        return !$this->isBackend();
    }

    public function isBackend(): bool
    {
        return function_exists('is_admin') && is_admin();
    }

    public function getBreadcrumbs(): string
    {
        return function_exists('yoast_breadcrumb') ? yoast_breadcrumb('<p id="breadcrumbs" class="mt-8">', '</p>', false) : '';
    }

    public function isScriptUrl(string $scriptUrl): bool
    {
        return $_SERVER['SCRIPT_URL'] ?? '' === $scriptUrl;
    }

    public function referrerIsNot(string $referrerPath): bool
    {
        return !$this->referrerIs($referrerPath);
    }

    public function referrerIs(string $referrerPath): bool
    {
        return $this->create($referrerPath) === $this->purgeGetParameters($_SERVER['HTTP_REFERER'] ?? '');
    }

    public function create(string $path, array $getParameters = [], bool $external = false): string
    {
        $url = ($external ?: $this->site()) . $this->sanitizePath($path);
        if (!empty($getParameters)) {
            $url = $this->decorate($url, $getParameters);
        }

        return $url;
    }

    protected function sanitizePath(string $path): string
    {
        $path = str_replace($this->home(), '', $path);

        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        if (!str_ends_with($path, '/')) {
            $path .= '/';
        }

        return $path;
    }

    public function home(): string
    {
        return function_exists('home_url') ? home_url() : '';
    }

    public function decorate(string $url, array $getParameters): string
    {
        $junction = '?';
        foreach ($getParameters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $url .= "$junction$key=$value";
            $junction = '&';
        }

        return $url;
    }

    #[Pure] public function isNot(string $path): bool
    {
        return !$this->is($path);
    }

    #[Pure] public function is(string $path): bool
    {
        return $this->sanitizePath($this->purgeGetParameters($_SERVER['REQUEST_URI'])) === $this->sanitizePath($path);
    }

    #[Pure] public function isNotOneOf(string ...$paths): bool
    {
        return !$this->isOneOf(...$paths);
    }

    #[Pure] public function isOneOf(string ...$paths): bool
    {
        $uri = $this->sanitizePath($this->purgeGetParameters($_SERVER['REQUEST_URI']));
        foreach ($paths as $path) {
            if ($this->sanitizePath($path) === $uri) {
                return true;
            }
        }

        return false;
    }

    #[Pure] public function notContains(string $path): bool
    {
        return !$this->contains($path);
    }

    #[Pure] public function contains(string $path): bool
    {
        return str_contains($this->sanitizePath($this->purgeGetParameters($_SERVER['REQUEST_URI'])), $this->sanitizePath($path));
    }

    public function back(): void
    {
        header('location: ' . $this->previous());
    }

    public function previous(): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? $this->home();
    }

    public function full(): string
    {
        return $this->current(true);
    }

    public function redirectToHome(): void
    {
        $this->redirect('/');
    }

    public function redirect(string $toPath): void
    {
        header('location: ' . $this->create($toPath));
    }

    /**
     * Usare la funzione passando un solo parametro. In caso di più parametri passati viene utilizzato il primo in ordine di dichiarazione
     *
     * @param string $is
     * @param string $contains
     * @param string $endsWith
     * @param string $startsWith
     *
     * @return bool
     */
    public function uri(string $is = '', string $contains = '', string $endsWith = '', string $startsWith = ''): bool
    {
        if (!empty($is)) {
            return $_SERVER['REQUEST_URI'] === $is;
        }

        if (!empty($contains)) {
            return str_contains($_SERVER['REQUEST_URI'], $contains);
        }

        if (!empty($endsWith)) {
            return str_ends_with($_SERVER['REQUEST_URI'], $endsWith);
        }

        return str_starts_with($_SERVER['REQUEST_URI'], $startsWith);
    }

}