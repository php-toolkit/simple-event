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
 * Class EventTrait
 * @package Toolkit\SimpleEvent
 */
trait MultiHandlersTrait
{
    /**
     * registered event names
     * @var array
     * [
     *  'event' => bool, // is once event
     * ]
     */
    private $events = [];

    /**
     * @var array
     */
    private $eventHandlers = [];

    /**
     * set the supported event names, if you need.
     *  if it is empty, will allow register any event.
     * @var string[]
     */
    protected $allowedEvents = [];

    /**
     * register a event handler
     * @param $event
     * @param callable $handler
     * @param bool $once
     */
    public function on(string $event, callable $handler, bool $once = false)
    {
        $event = \trim($event);

        if ($this->isAllowedEvent($event)) {
            $this->events[$event] = $once;
            $this->eventHandlers[$event][] = $handler;
        }
    }

    /**
     * register a once event handler
     * @param string $event
     * @param callable $handler
     */
    public function once(string $event, callable $handler)
    {
        $this->on($event, $handler, true);
    }

    /**
     * trigger event
     * @param string $event
     * @param array ...$args
     * @return bool
     */
    public function fire(string $event, ...$args)
    {
        if (!isset($this->events[$event])) {
            return false;
        }

        // call event handlers of the event.
        foreach ((array)$this->eventHandlers[$event] as $cb) {
            // return FALSE to stop go on handle.
            if (false === PhpHelper::call($cb, ...$args)) {
                break;
            }
        }

        // is a once event, remove it
        if ($this->events[$event]) {
            return $this->off($event);
        }

        return true;
    }

    /**
     * remove event and it's handlers
     * @param string $event
     * @return mixed
     */
    public function off(string $event)
    {
        if ($this->hasEvent($event)) {
            $handler = $this->eventHandlers[$event];

            unset($this->events[$event], $this->eventHandlers[$event]);
            return $handler;
        }

        return null;
    }

    /**
     * clearEvents
     */
    public function clearEvents()
    {
        $this->events = $this->eventHandlers = [];
    }

    /**
     * @param string $event
     * @return bool
     */
    public function hasEvent(string $event): bool
    {
        return isset($this->events[$event]);
    }

    /**
     * @param string $event
     * @return bool
     */
    public function isOnceEvent(string $event): bool
    {
        return $this->events[$event] ?? false;
    }

    /**
     * check $name is a supported event name
     * @param $event
     * @return bool
     */
    public function isAllowedEvent(string $event): bool
    {
        if (!$event) {
            return false;
        }

        if ($ets = $this->allowedEvents) {
            return \in_array($event, $ets, true);
        }

        return true;
    }

    /**
     * @return array
     */
    public function getAllowedEvents(): array
    {
        return $this->allowedEvents;
    }

    /**
     * @param array $allowedEvents
     */
    public function setAllowedEvents(array $allowedEvents)
    {
        $this->allowedEvents = $allowedEvents;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @return int
     */
    public function getEventCount(): int
    {
        return \count($this->events);
    }
}
