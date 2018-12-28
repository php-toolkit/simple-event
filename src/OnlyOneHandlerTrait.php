<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-04-28
 * Time: 17:03
 */

namespace Toolkit\SimpleEvent;

use Toolkit\PhpUtil\Php;

/**
 * Trait OnlyOneHandlerTrait
 *  - 简洁版的事件处理trait，一个事件只允许一个处理者
 * @package Toolkit\SimpleEvent
 */
trait OnlyOneHandlerTrait
{
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
     * register a event callback
     * @param string $name event name
     * @param callable $cb event callback
     * @param bool $replace replace exists's event cb
     */
    public function on(string $name, callable $cb, bool $replace = false)
    {
        if ($replace || !isset($this->eventHandlers[$name])) {
            $this->eventHandlers[$name] = $cb;
        }
    }

    /**
     * @param string $name
     * @param array ...$args
     * @return mixed
     */
    protected function fire(string $name, ...$args)
    {
        if (!isset($this->eventHandlers[$name]) || !($cb = $this->eventHandlers[$name])) {
            return null;
        }

        return Php::call($cb, ...$args);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function off(string $name)
    {
        $cb = null;

        if (isset($this->eventHandlers[$name])) {
            $cb = $this->eventHandlers[$name];
            unset($this->eventHandlers[$name]);
        }

        return $cb;
    }

    /**
     * @param string $event
     * @return bool
     */
    public function isAllowedEvent(string $event): bool
    {
        return $this->allowedEvents && \in_array($event, $this->allowedEvents, true);
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
     * @param string $name
     * @return mixed
     */
    public function getEventHandler(string $name)
    {
        return $this->eventHandlers[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getEventHandlers(): array
    {
        return $this->eventHandlers;
    }

    /**
     * @return int
     */
    public function getEventCount(): int
    {
        return \count($this->eventHandlers);
    }

    /**
     * clearEvents
     */
    public function clearEvents(): void
    {
        $this->eventHandlers = [];
    }
}
