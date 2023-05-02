<?php

namespace PE\Component\Event;

interface EventInterface
{
    /**
     * Stop event propagation
     */
    public function stop(): void;

    /**
     * Check if propagation stopped
     *
     * @return bool
     */
    public function isStopped(): bool;
}