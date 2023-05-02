<?php

namespace PE\Component\Event;

final class Emitter implements EmitterInterface
{
    private array $listeners = [];
    private array $optimized = [];

    public function attach(string $event, callable $listener, int $priority = 0): void
    {
        $this->listeners[$event][$priority][] = $listener;
        unset($this->optimized[$event]);
    }

    public function detach(string $event, callable $listener): void
    {
        if (empty($this->listeners[$event])) {
            return;// @codeCoverageIgnore
        }

        unset($this->optimized[$event]);
        foreach ($this->listeners[$event] as $priority => $listeners) {
            foreach ($listeners as $k => $v) {
                if ($v === $listener) {
                    unset($listeners[$k]);
                }
            }

            if ($listeners) {
                $this->listeners[$event][$priority] = $listeners;
            } else {
                unset($this->listeners[$event][$priority]);
            }
        }
    }

    public function dispatch(object $event): void
    {
        $name = $event instanceof Event ? $event->getName() : get_class($event);
        if (empty($this->listeners[$name])) {
            return;// @codeCoverageIgnore
        }

        $listeners = $this->optimized[$name] ?? null;
        if (null === $listeners) {
            $listeners = $this->listeners[$name];
            ksort($listeners);
            $this->optimized[$name] = array_merge(...$listeners);
        }

        foreach ($this->optimized[$name] as $listener) {
            $arguments = [$event];
            if ($event instanceof Event) {
                try {
                    if (Event::class !== (string) (new \ReflectionParameter($listener, 0))->getType()) {
                        $arguments = $event->getArgs();
                    }
                } catch (\Throwable $exception) {}// @codeCoverageIgnore
            }
            $listener(...$arguments);
            if ($event instanceof EventInterface && $event->isStopped()) {
                return;
            }
        }
    }
}
