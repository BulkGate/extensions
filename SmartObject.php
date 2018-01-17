<?php
namespace BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class SmartObject
{
    public function __get($name)
    {
        $class = get_class($this);

        if(property_exists($class, $name))
        {
            return $this->$name;
        }
        throw new MemberAccessException("Cannot read an undeclared property $class::\$$name.");
    }

    public function __set($name, $value)
    {
        $class = get_class($this);

        if(property_exists($class, $name))
        {
            $this->$name = $value;
        }
        throw new MemberAccessException("Cannot write an undeclared property $class::\$$name.");
    }
}