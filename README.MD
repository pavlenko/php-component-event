## Requirements

The following versions of PHP are supported.

* PHP 7.4+

## Installation

To install, use composer:

```
php composer.phar require pe/component-event
```

## Usage

Minimal usage:

```php
namespace PE\Component\Event;

// Create emitter instance, maybe in some DI container
$emitter = new Emitter();

// Attach listener to some event
$emitter->attach('event_name', function ($foo, $bar) {
    // do something
});
// Also you can attach as generic listener for allow stop propagation
$emitter->attach('event_name', function (Event $event) {
    $event->stop();// <-- call for stop propagation
});

// Dispatch event somewhere in logic
$emitter->dispatch(new Event('event_name', $foo, $bar));
```

Extended usage:

```php
<?php
namespace PE\Component\Event;

// Create your own event class for store specific payload or modify it in listeners
class SomeEvent
{
    public string $message = 'A';
}

$emitter = new Emitter();

// Listener A, it's important to first arg be same as event class
$emitter->attach(SomeEvent::class, function (SomeEvent $event) {
    $event->message = 'B';
});
// Listener B
$emitter->attach(SomeEvent::class, function (SomeEvent $event) {
    echo $event->message;// <-- here message is "B"
});
$emitter->dispatch(new SomeEvent());
```

Note: each listener can be attached with priority,
the lower the priority value - the earlier the listener will fire