<?php

namespace PE\Component\Event;

/**
 * @codeCoverageIgnore
 */
final class Event implements EventInterface
{
    private bool $stopped = false;
    private string $name;
    private array $args;

    public function __construct(string $name, ...$args)
    {
        $this->name = $name;
        $this->args = $args;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function setArgs(...$args): void
    {
        $this->args = $args;
    }

    public function getArg(int $num, $default = null)
    {
        return $this->args[$num] ?? $default;
    }

    public function stop(): void
    {
        $this->stopped = true;
    }

    public function isStopped(): bool
    {
        return $this->stopped;
    }
}
