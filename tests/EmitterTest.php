<?php

namespace PE\Component\Event\Tests;

use PE\Component\Event\Emitter;
use PE\Component\Event\Event;
use PHPUnit\Framework\TestCase;

final class EmitterTest extends TestCase
{
    public function testDispatchListenerWithSimpleArgs()
    {
        $arg0     = 'foo';
        $callable = function (string $arg1) use ($arg0) {
            self::assertSame($arg0, $arg1);
        };

        $emitter = new Emitter();
        $emitter->attach('foo', $callable);
        $emitter->dispatch(new Event('foo', $arg0));
    }

    public function testDispatchListenerWithCustomEventArg()
    {
        $event    = new \stdClass();
        $callable = function ($arg) use ($event) {
            self::assertSame($arg, $event);
        };

        $emitter = new Emitter();
        $emitter->attach(\stdClass::class, $callable);
        $emitter->dispatch($event);
    }

    public function testDispatchListenerWithGenericEventArg()
    {
        $arg0     = 'foo';
        $callable = function (Event $event) use ($arg0) {
            self::assertSame($arg0, $event->getArg(0));
        };

        $emitter = new Emitter();
        $emitter->attach('foo', $callable);
        $emitter->dispatch(new Event('foo', $arg0));
    }

    public function testDispatchListenerWithGenericEventStopped()
    {
        $callable = function (Event $event) {
            $event->stop();
        };

        $emitter = new Emitter();
        $emitter->attach('foo', $callable);
        $emitter->dispatch($event = new Event('foo'));

        self::assertTrue($event->isStopped());
    }

    public function testDispatchDetachedListener()
    {
        $counter  = 0;
        $callable = function (Event $event) use (&$counter) {
            $counter++;
        };

        $emitter = new Emitter();

        $emitter->attach('foo', $callable);
        $emitter->attach('foo', $f = fn() => null);
        $emitter->dispatch(new Event('foo'));

        $emitter->detach('foo', $callable);
        $emitter->detach('foo', $f);
        $emitter->dispatch(new Event('foo'));

        self::assertSame(1, $counter);
    }
}