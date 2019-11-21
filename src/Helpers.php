<?php
namespace BulkGate\Extensions;

use DateTime;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Helpers extends Strict
{
    public static function outOfStockCheck(Settings $settings, $product_id)
    {
        $result = false;

        $list = $settings->load('static:out_of_stock', false);

        $list = $list !== false ? unserialize($list) : [];

        if (is_array($list)) {
            foreach ($list as $key => $time) {
                if ($time < time()) {
                    unset($list[(string)$key]);
                }
            }
        } else {
            $list = [];
        }
        if (!isset($list[(string)$product_id])) {
            $list[(string)$product_id] = (new DateTime('now + 1 day'))->getTimestamp();
            $result = true;
        }

        $settings->set('static:out_of_stock', serialize($list));

        return $result;
    }
}
