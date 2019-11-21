<?php
namespace BulkGate\Extensions;

use BulkGate;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Compress
{
    public static function compress($data, $encoding_mode = 9)
    {
        return base64_encode(gzencode(serialize($data), $encoding_mode));
    }

    public static function decompress($data)
    {
        if ($data) {
            return unserialize(gzinflate(substr(base64_decode($data), 10, -8)));
        }
        return false;
    }
}
