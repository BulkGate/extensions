<?php
namespace BulkGate\Extensions\IO;

use BulkGate;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Request extends BulkGate\Extensions\SmartObject
{
    const CONTENT_TYPE_JSON = 'application/json';

    const CONTENT_TYPE_ZIP = 'application/zip';

    private $url;

    private $data = array();

    private $content_type;

    public function __construct($url, array $data = array(), $compress = false)
    {
        $this->setUrl($url);
        $this->setData($data, $compress);
    }

    public function setData(array $data = array(), $compress = false)
    {
        $this->data = (array) $data;
        $this->content_type = $compress ? self::CONTENT_TYPE_ZIP : self::CONTENT_TYPE_JSON;

        return $this;
    }

    public function setUrl($url)
    {
        $this->url = (string) $url;

        return $this;
    }

    public function getData()
    {
        try
        {
            if($this->content_type === self::CONTENT_TYPE_ZIP)
            {
                return BulkGate\Extensions\Compress::compress(BulkGate\Extensions\Json::encode($this->data));
            }
            else
            {
                return BulkGate\Extensions\Json::encode($this->data);
            }
        }
        catch (BulkGate\Extensions\JsonException $e)
        {
            throw new InvalidRequestException;
        }
    }

    public function getUrl()
    {
        return (string) $this->url;
    }

    public function getContentType()
    {
        return (string) $this->content_type;
    }
}
