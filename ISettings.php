<?php
namespace BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
interface ISettings
{
    public function load($settings_key, $default = false);

    public function set($key, $value, array $meta = array());

    public function delete($key = null);

    /** @return array */
    public function synchronize();
}