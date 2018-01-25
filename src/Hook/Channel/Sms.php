<?php
namespace BulkGate\Extensions\Hook\Channel;

use BulkGate;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Sms extends BulkGate\Extensions\SmartObject implements IChannel
{
    /** @var bool */
    private $active = false;

    /** @var string */
    private $message = '';

    /** @var bool */
    private $unicode = false;

    /** @var bool */
    private $flash = false;

    /** @var string */
    private $sender_type = "gSystem";

    /** @var string */
    private $sender_value = "";

    /** @var bool */
    private $customer = false;

    /** @var array */
    private $admins = array();

    public function __construct(array $data)
    {
        foreach($data as $key => $value)
        {
            try
            {
                $this->{$key} = $value;
            }
            catch (BulkGate\Extensions\MemberAccessException $e)
            {
            }
        }
    }

    public function isActive()
    {
        return (bool) $this->active;
    }

    public function toArray()
    {
        return array(
            'active'         => (bool) $this->active,
            'message'        => (string) $this->message,
            'unicode'        => (bool) $this->unicode,
            'flash'          => (bool) $this->flash,
            'sender_type'    => (string) $this->sender_type,
            'sender_value'   => (string) $this->sender_value,
            'customer'       => (bool) $this->customer,
            'admins'         => (array) $this->admins
        );
    }
}