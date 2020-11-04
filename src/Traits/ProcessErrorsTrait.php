<?php

namespace Deviddev\BillingoApiV3Wrapper\Traits;

trait ProcessErrorsTrait
{

    /**
     * Store error message
     *
     * @var string
     */
    public $error;

    /**
     * Set error
     *
     * @param string $error
     *
     * @return self
     */
    public function error(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error message json response
     *
     * @return string
     */
    public function getJson(): string
    {
        return \json_encode(\json_decode($this->getJsonFromErrorString(), true), JSON_FORCE_OBJECT);
    }

    /**
     * Get json from error string
     *
     * @return string
     */
    public function getJsonFromErrorString(): string
    {
        return substr(
            $this->error,
            strpos($this->error, "{"),
            strpos($this->error, "}")
        );
    }
}
