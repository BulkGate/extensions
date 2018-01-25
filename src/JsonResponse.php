<?php
namespace BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class JsonResponse extends SmartObject
{
    public static function send($data)
    {
        header('Content-Type: application/json');
        echo Json::encode($data);
        exit;
    }
}
