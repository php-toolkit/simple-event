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
 * Trait OnlyOneHandlerStaticTrait
 *  - 简洁版的事件处理trait，一个事件只允许一个处理者
 * @package Toolkit\SimpleEvent
 */
trait OnlyOneHandlerStaticTrait
{
    /**
     * @var array
     */
    private static $eventHandlers = [];

    /**
     * register a event callback
     * @param string $name event name
     * @param callable $cb event callback
     * @param bool $replace replace exists's event cb
     */
    public static function on(string $name, callable $cb, bool $replace = false)
    {
        if ($replace || !isset(self::$eventHandlers[$name])) {
            self::$eventHandlers[$name] = $cb;
        }
    }

    /**
     * @param string $name
     * @param array ...$args
     * @return mixed
     */
    public static function fire(string $name, ...$args)
    {
        if (!isset(self::$eventHandlers[$name]) || !($cb = self::$eventHandlers[$name])) {
            return null;
        }

        return Php::call($cb, ...$args);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function off(string $name)
    {
        $cb = null;

        if (isset(self::$eventHandlers[$name])) {
            $cb = self::$eventHandlers[$name];
            unset(self::$eventHandlers[$name]);
        }

        return $cb;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function getEventHandler(string $name)
    {
        return self::$eventHandlers[$name] ?? null;
    }

    /**
     * @return array
     */
    public static function getEventHandlers(): array
    {
        return self::$eventHandlers;
    }

    /**
     * @return int
     */
    public static function getEventCount(): int
    {
        return \count(self::$eventHandlers);
    }
}
