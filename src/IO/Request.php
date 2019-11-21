<?php
namespace BulkGate\Extensions\IO;

use BulkGate;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Request extends BulkGate\Extensions\Strict
{
    const CONTENT_TYPE_JSON = 'application/json';

    const CONTENT_TYPE_ZIP = 'application/zip';

    /** @var string */
    private $url;

    /** @var array */
    private $data = [];

    /** @var string */
    private $content_type;

    /** @var int */
    private $timeout;

    public function __construct($url, array $data = [], $compress = false, $timeout = 20)
    {
        $this->setUrl($url);
        $this->setData($data, $compress);
        $this->timeout = max(3 /** min timeout */, (int) $timeout);
    }

    public function setData(array $data = [], $compress = false)
    {
        $this->data = $data;
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
        try {
            if ($this->content_type === self::CONTENT_TYPE_ZIP) {
                return BulkGate\Extensions\Compress::compress(BulkGate\Extensions\Json::encode($this->data));
            }
            return BulkGate\Extensions\Json::encode($this->data);
        } catch (BulkGate\Extensions\JsonException $e) {
            throw new InvalidRequestException;
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getContentType()
    {
        return (string) $this->content_type;
    }

    public function getTimeout()
    {
        return (int) $this->timeout;
    }
}
