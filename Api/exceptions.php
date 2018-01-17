<?php
namespace BulkGate\Extensions\Api;

use BulkGate\Extensions;

/**
 * @author Lukáš Piják 2017 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class ConnectionException extends Extensions\Exception {}

class InvalidRequestException extends ConnectionException {}

class UnknownActionException extends ConnectionException {}

class MethodNotAllowedException extends ConnectionException {}
