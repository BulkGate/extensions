<?php
namespace BulkGate\Extensions\IO;

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
 * @package BulkGate\Extensions\IO
 */
class InvalidRequestException extends ConnectionException
{
}

/**
 * Class InvalidResultException
 * @package BulkGate\Extensions\IO
 */
class InvalidResultException extends ConnectionException
{
}

/**
 * Class AuthenticateException
 * @package BulkGate\Extensions\IO
 */
class AuthenticateException extends ConnectionException
{
}
