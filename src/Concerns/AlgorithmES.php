<?php

namespace Hanafalah\ApiHelper\Concerns;

trait AlgorithmES
{
    use AlgorithmRS;

    /**
     * Encrypts the payload using the RS256 algorithm, and returns the
     * encrypted token.
     *
     * @return string The encrypted token.
     */
    protected function algorithmES()
    {
        return $this->algorithmRS();
    }

    protected function setEsKeys(): self
    {
        $this->setRsKeys();
        return $this;
    }

    /**
     * Encrypts the payload using the RS256 algorithm, and returns the
     * encrypted token.
     *
     * @return string The encrypted token.
     */
    protected function processES()
    {
        return $this->processRS();
    }
}
