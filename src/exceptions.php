<?php
namespace BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Exception extends \Exception
{
}

/**
 * Class StrictException
 * @package BulkGate\Extensions
 */
class StrictException extends Exception
{
}

/**
 * Class JsonException
 * @package BulkGate\Extensions
 */
class JsonException extends Exception
{
}

/**
 * Class InvalidKeyException
 * @package BulkGate\Extensions
 */
class InvalidKeyException extends Exception
{
}

/**
 * Class ServiceNotFoundException
 * @package BulkGate\Extensions
 */
class ServiceNotFoundException extends Exception
{
}
