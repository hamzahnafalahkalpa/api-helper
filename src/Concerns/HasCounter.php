<?php

namespace Zahzah\ApiHelper\Concerns;

trait HasCounter
{
    /**
     * Increment the total_hit column of the current ApiAccess.
     *
     * @return self
     */
    protected function updateCounter(): self{
        $total_hit = $this->getApiAccess()->total_hit ?? 0;
        $this->getApiAccess()->total_hit = $total_hit++;
        $this->getApiAccess()->save();
        return $this;
    }

    /**
     * Increment the total_hit of the current ApiAccess and save it.
     *
     * @return self
     */
    public function addCounter():self{
        $this->updateCounter();
        $this->getApiAccess()->save();
        return $this;
    }
}