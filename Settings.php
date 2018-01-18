<?php
namespace BulkGate\Extensions;

use BulkGate;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Settings extends SmartObject implements ISettings
{
    /** @var array */
    public $data = array();

    /** @var BulkGate\Extensions\Database\IDatabase */
    private $db;

    public function __construct(BulkGate\Extensions\Database\IDatabase $database)
    {
        $this->db = $database;
    }

    public function load($settings_key, $default = false)
    {
        list($scope, $key) = BulkGate\Extensions\Key::decode($settings_key);

        if(isset($this->data[$scope]))
        {
            if(isset($this->data[$scope][$key]))
            {
                return $this->data[$scope][$key];
            }
            else if(isset($this->data[$scope]) && !isset($this->data[$scope][$key]) && $key !== null)
            {
                return $default;
            }
            else
            {
                return $this->data[$scope];
            }
        }
        else
        {
            $result = $this->db->execute('SELECT * FROM `'.$this->db->prefix().'bulkgate_module` WHERE `scope` = "'.$this->db->escape($scope).'" AND `synchronize_flag` != "delete" ORDER BY `order`');

            if($result->getNumRows() > 0)
            {
                foreach ($result as $item)
                {
                    switch($item->type)
                    {
                        case "text":
                            $this->data[$scope][$item->key] = (string) $item->value;
                        break;
                        case "int":
                            $this->data[$scope][$item->key] = (int) $item->value;
                        break;
                        case "float":
                            $this->data[$scope][$item->key] = (float) $item->value;
                        break;
                        case "bool":
                            $this->data[$scope][$item->key] = (bool) $item->value;
                        break;
                        case "json":
                            try
                            {
                                $this->data[$scope][$item->key] = BulkGate\Extensions\Json::decode($item->value);
                            }
                            catch (BulkGate\Extensions\JsonException $e)
                            {
                                $this->data[$scope][$item->key] = null;
                            }

                        break;
                    }
                }
            }
            else
            {
                $this->data[$scope] = false;
            }
            return $this->load($settings_key);
        }
    }

    public function set($key, $value, array $meta = array())
    {
        if(!isset($meta['datetime']))
        {
            $meta['datetime'] = time();
        }

        list($scope, $key) = BulkGate\Extensions\Key::decode($key);

        $result = $this->db->execute('SELECT * FROM `'.$this->db->prefix().'bulkgate_module` WHERE `scope` = "'.$this->db->escape($scope).'" AND `key` = "'.$this->db->escape($key).'"');

        if($result->getNumRows() > 0)
        {
            $this->db->execute('UPDATE `'.$this->db->prefix().'bulkgate_module` SET value = "'.$this->db->escape($value).'", `datetime` = "'.$this->db->escape($meta['datetime']).'" '.$this->parseMeta($meta).' WHERE `scope` = "'.$this->db->escape($scope).'" AND `key` = "'.$this->db->escape($key).'"');
        }
        else
        {
            $this->db->execute('
                        INSERT INTO `'.$this->db->prefix().'bulkgate_module` SET 
                            `scope`="'.$this->db->escape($scope).'",
                            `key`="'.$this->db->escape($key).'",
                            `value`="'.$this->db->escape($value).'"'.$this->parseMeta($meta).'
            ');
        }
    }

    public function delete($key = null)
    {
        if($key === null)
        {
            $this->db->execute('
                        DELETE FROM `'.$this->db->prefix().'bulkgate_module` WHERE `synchronize_flag` = "delete"
            ');
        }
        else
        {
            list($scope, $key) = BulkGate\Extensions\Key::decode($key);

            $this->db->execute('
                        DELETE FROM `'.$this->db->prefix().'bulkgate_module` WHERE `scope` = "'.$this->db->escape($scope).'" AND `key` = "'.$this->db->escape($key).'"
            ');
        }
    }

    public function synchronize()
    {
        $output = array();

        $result = $this->db->execute('SELECT * FROM `'.$this->db->prefix().'bulkgate_module` WHERE `scope` != "static"')->getRows();

        foreach($result as $row)
        {
            $output[$row->scope.':'.$row->key] = $row;
        }

        return $output;
    }

    public function install()
    {
        $this->db->execute("
            CREATE TABLE IF NOT EXISTS `".$this->db->prefix()."bulkgate_module` (
            `scope` varchar(50) NOT NULL DEFAULT 'main',
            `key` varchar(50) NOT NULL,
            `type` enum('text','int','float','bool','json') DEFAULT 'text',
            `value` text NOT NULL,
            
            `datetime` datetime DEFAULT NULL,
            `order` int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`scope`,`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    public function uninstall()
    {
        if ($this->load('main:delete_db', false))
        {
            $this->db->execute("DROP TABLE IF EXISTS `" . $this->db->prefix() . "bulkgate_module`");
        }
    }

    private function parseMeta(array $meta)
    {
        $output = array();

        foreach($meta as $key => $item)
        {
            switch ($key)
            {
                case 'type':
                    $output[] = '`type`="'.$this->db->escape($this->checkType($item)).'"';
                break;
                case 'datetime':
                    $output[] = '`datetime`="'.$this->db->escape($this->formatDate($item)).'"';
                break;
                case 'order':
                    $output[] = '`order`="'.$this->db->escape((int) $item).'"';
                break;
                case 'synchronize_flag':
                    $output[] = '`synchronize_flag`="'.$this->db->escape($this->checkFlag($item)).'"';
                break;
            }
        }
        return count($output) > 0 ? ','.implode(',', $output) : '';
    }

    private function formatDate($date)
    {
        if($date instanceof \DateTime)
        {
            return $date->getTimestamp();
        }
        else if(is_string($date))
        {
            return strtotime($date);
        }
        else if(is_int($date))
        {
            return $date;
        }
        return time();
    }

    private $types = array('text','int','float','bool','json');

    private function checkType($type, $default = 'text')
    {
        if(in_array((string) $type, $this->types))
        {
            return $type;
        }
        return $default;
    }

    private $flags = array('none','add','change','delete');

    private function checkFlag($flag, $default = 'none')
    {
        if(in_array((string) $flag, $this->flags))
        {
            return $flag;
        }
        return $default;
    }
}