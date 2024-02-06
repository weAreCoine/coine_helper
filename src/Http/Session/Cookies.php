<?php

namespace Coine\WpHelper\Http\Session;


use Coine\WpHelper\Abstracts\Globals;

class Cookies extends Globals
{
    protected array $options;

    public function __construct()
    {
        $this->options = [
            'path' => '/',
            'domain' => server()->get('HTTP_HOST'),
            'secure' => true,
            'httponly' => false,
            'samesite' => 'Lax',
        ];
    }

    public function delete(string ...$keys)
    {
        foreach ($keys as $key) {
            $this->set($key, '', -YEAR_IN_SECONDS);
        }
    }

    /**
     *
     * NB. Ricorda che questo metodo deve essere richiamato prima di qualsiasi output. Puoi usare l'action 'init'
     *
     * @param string $newKey
     * @param string $newValue
     * @param int $durationInSeconds
     *
     * @return $this
     */
    public function set(string $newKey, string $newValue, int $durationInSeconds = DAY_IN_SECONDS): self
    {

        $args = array_merge($this->options, [
            'expires' => time() + $durationInSeconds,

        ]);

        setcookie($newKey, $newValue, $args);

        return $this;
    }

    protected function getData(): array
    {
        return $_COOKIE;
    }
}