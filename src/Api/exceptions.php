<?php
namespace BulkGate\Extensions\Api;

use BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class ConnectionException extends Extensions\Exception
{
}

/**
 * Class InvalidRequestException
 * @package BulkGate\Extensions\Api
 */
class InvalidRequestException extends ConnectionException
{
}

/**
 * Class UnknownActionException
 * @package BulkGate\Extensions\Api
 */
class UnknownActionException extends ConnectionException
{
}

/**
 * Class MethodNotAllowedException
 * @package BulkGate\Extensions\Api
 */
class MethodNotAllowedException extends ConnectionException
{
}
