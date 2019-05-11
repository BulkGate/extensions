<?php
namespace BulkGate\Extensions;

use BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Iterator extends Extensions\Strict implements \Iterator
{
    /** @var array */
    protected $array = [];

    /** @var int */
    private $position = 0;

    public function __construct(array $rows)
    {
        $this->array = $rows;
        $this->position = 0;
    }

    public function get($key)
    {
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }

    public function set($key, $value)
    {
        return $this->array[$key] = $value;
    }

    public function rewind()
    {
        reset($this->array);
    }

    public function current()
    {
        return current($this->array);
    }

    public function key()
    {
        return key($this->array);
    }

    public function next()
    {
        next($this->array);
    }

    public function valid()
    {
        return key($this->array) !== null;
    }

    public function count()
    {
        return count($this->array);
    }

    public function add($value)
    {
        $this->array[] = $value;
    }
}
