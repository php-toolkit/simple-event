<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-03-27
 * Time: 16:17
 */

namespace Toolkit\SimpleEvent;

use Toolkit\PhpUtil\PhpHelper;

/**
 * Class MultiHandlersStaticTrait
 * @package Toolkit\SimpleEvent
 */
trait MultiHandlersStaticTrait
{
    /**
     * registered event names
     * @var array
     * [
     *  'event' => bool, // is once event
     * ]
     */
    private static $events = [];

    /**
     * @var array
     */
    private static $eventHandlers = [];

    /**
     * set the supported event names, if you need.
     *  if it is empty, will allow register any event.
     * @var string[]
     */
    protected static $allowedEvents = [];

    /**
     * register a event handler
     * @param $event
     * @param callable $handler
     * @param bool $once
     */
    public static function on(string $event, callable $handler, bool $once = false)
    {
        $event = \trim($event);

        if (static::isAllowedEvent($event)) {
            static::$events[$event] = $once;
            static::$eventHandlers[$event][] = $handler;
        }
    }

    /**
     * register a once event handler
     * @param $event
     * @param callable $handler
     */
    public static function once($event, callable $handler)
    {
        static::on($event, $handler, true);
    }

    /**
     * trigger event
     * @param string $event
     * @param array ...$args
     * @return bool
     */
    public static function fire(string $event, ...$args)
    {
        if (!isset(static::$events[$event])) {
            return false;
        }

        // call event handlers of the event.
        foreach ((array)static::$eventHandlers[$event] as $cb) {
            // return FALSE to stop go on handle.
            if (false === PhpHelper::call($cb, ...$args)) {
                break;
            }
        }

        // is a once event, remove it
        if (static::$events[$event]) {
            return static::off($event);
        }

        return true;
    }

    /**
     * remove event and it's handlers
     * @param string $event
     * @return mixed
     */
    public static function off(string $event)
    {
        if (static::hasEvent($event)) {
            $handler = static::$eventHandlers[$event];

            unset(static::$events[$event], static::$eventHandlers[$event]);
            return $handler;
        }

        return null;
    }

    /**
     * clearEvents
     */
    public static function clearEvents(): void
    {
        static::$events = static::$eventHandlers = [];
    }

    /**
     * @param string $event
     * @return bool
     */
    public static function hasEvent(string $event): bool
    {
        return isset(static::$events[$event]);
    }

    /**
     * @param string $event
     * @return bool
     */
    public static function isOnceEvent(string $event): bool
    {
        return static::$events[$event] ?? false;
    }

    /**
     * check $name is a supported event name
     * @param $event
     * @return bool
     */
    public static function isAllowedEvent(string $event): bool
    {
        if (!$event) {
            return false;
        }

        if ($ets = static::$allowedEvents) {
            return \in_array($event, $ets, true);
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getAllowedEvents(): array
    {
        return static::$allowedEvents;
    }

    /**
     * @param array $allowedEvents
     */
    public static function setAllowedEvents(array $allowedEvents)
    {
        static::$allowedEvents = $allowedEvents;
    }

    /**
     * @return array
     */
    public static function getEvents(): array
    {
        return static::$events;
    }

    /**
     * @return int
     */
    public static function getEventCount(): int
    {
        return \count(static::$events);
    }
}
