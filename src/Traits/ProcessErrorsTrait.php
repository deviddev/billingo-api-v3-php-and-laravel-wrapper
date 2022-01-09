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
     * Get error message response
     *
     * @return string
     */
    public function response(): string
    {
        if ($this->isJson($this->error)) {
            return \json_encode(\json_decode($this->getJsonFromErrorString(), true), JSON_FORCE_OBJECT);
        }

        return $this->error;
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

    /**
     * Check that error string is json encoded
     *
     * @param string $error
     *
     * @return bool
     */
    public function isJson(string $error): bool
    {
        json_decode($error);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
