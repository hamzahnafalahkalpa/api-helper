<?php

namespace Zahzah\ApiHelper\Concerns;

trait InitConfig{
    protected static $__api_helper_config;
    protected static $__authorize_model, $__authorizing, $__encryption;
    protected static $__initialized = false;

    protected function setup(): self{
        self::$__api_helper_config = config('api-helper');
        $this->authorizing();
        $this->encryption();
        return $this;
    }

    private function notReady(): bool{
        return !self::$__initialized;
    }

    private function initialized(): self{
        self::$__initialized = true;
        return $this;
    }

    /**
     * Get the encryption method for generating the token.
     *
     * @return string The encryption class.
     */
    public function encryption(){
        return self::$__encryption = self::$__api_helper_config['encryption'];
    }

    /**
     * Get the authorizing instance.
     *
     * @return object The authorizing instance.
     */
    public function authorizing(): object{
        self::$__authorizing = self::$__api_helper_config['authorizing'];
        return app(self::$__authorizing);
    }

    /**
     * Get the authorization model configuration.
     *
     * @return array The authorization model configuration.
     */
    public function authorizationConfig(): array{
        return self::$__authorize_model = self::$__api_helper_config['authorization_model'];
    }

    /**
     * Set the authorizing class, and reset the setup.
     *
     * @param  string|null  $class
     * @return self
     */
    public function setAuthorizing(? string $class = null): self{
        if (isset($class)) config(['api-helper.authorizing' => $class]);
        $this->setup();
        return $this;
    }
}