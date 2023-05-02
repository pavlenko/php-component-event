<?php

namespace PE\Component\Event;

interface EmitterInterface
{
    public function attach(string $event, callable $listener, int $priority = 0);

    public function detach(string $event, callable $listener);

    public function dispatch(object $event): void;
}