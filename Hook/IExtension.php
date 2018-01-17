<?php
namespace BulkGate\Extensions\Hook;

use BulkGate\Extensions\Hook;
use BulkGate\Extensions\Database;

/**
 * @author Lukáš Piják 2017 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
interface IExtension
{
    public function extend(Database\IDatabase $database, Hook\Variables $variables);
}