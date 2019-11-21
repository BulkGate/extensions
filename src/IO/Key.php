<?php
namespace BulkGate\Extensions\IO;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Key
{
    const DEFAULT_REDUCER = '_generic';

    const DEFAULT_CONTAINER = 'server';

    const DEFAULT_VARIABLE = '_empty';

    public static function decode($key)
    {
        if (preg_match(
            '~^(?<reducer>[a-zA-Z0-9_-]*):(?<container>[a-zA-Z0-9_-]*):(?<name>[a-zA-Z0-9_-]*)$~',
            $key,
            $match
        )) {
            return [$match['reducer'] ?: self::DEFAULT_REDUCER, $match['container'] ?:
                self::DEFAULT_CONTAINER, $match['name'] ?: self::DEFAULT_VARIABLE];
        }
        if (preg_match('~^(?<container>[a-zA-Z0-9_-]*):(?<name>[a-zA-Z0-9_-]*)$~', $key, $match)) {
            return [self::DEFAULT_REDUCER, $match['container'] ?: self::DEFAULT_CONTAINER, $match['name'] ?:
                self::DEFAULT_VARIABLE];
        }
        if (preg_match('~^(?<name>[a-zA-Z0-9_-]*)$~', $key, $match)) {
            return [self::DEFAULT_REDUCER, self::DEFAULT_CONTAINER, $match['name'] ?: self::DEFAULT_VARIABLE];
        }
        throw new InvalidResultException;
    }

    public static function encode($name, $container, $reducer)
    {
        return $reducer . ':' . $container . ':' . $name;
    }
}
