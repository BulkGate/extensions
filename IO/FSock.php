<?php
namespace BulkGate\Extensions\IO;

use BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class FSock extends Extensions\SmartObject implements IConnection
{
    /** @var  string */
    private $application_id;

    /** @var  string */
    private $application_token;

    /** @var string */
    private $application_url;

    /** @var string */
    private $application_product;

    /**
     * Connection constructor.
     * @param $application_id
     * @param $application_token
     * @param $application_url
     * @param $application_product
     */
    public function __construct($application_id, $application_token, $application_url, $application_product)
    {
        $this->application_id = $application_id;
        $this->application_token = $application_token;
        $this->application_url = $application_url;
        $this->application_product = $application_product;
    }

    /**
     * @param Request $request
     * @throws ConnectionException
     * @return Response
     */
    public function run(Request $request)
    {
        $context = stream_context_create(array('http' => array(
            'method' => 'POST',
            'header' => array(
                'Content-type: ' . $request->getContentType(),
                'X-BulkGate-Application-ID: ' . (string) $this->application_id,
                'X-BulkGate-Application-Token: ' . (string) $this->application_token,
                'X-BulkGate-Application-Url: ' . (string) $this->application_url,
                'X-BulkGate-Application-Product: '. (string) $this->application_product
            ),
            'content' => $request->getData(),
            "ignore_errors" => true,
        )));

        $connection = fopen($request->getUrl(), 'r', false, $context);

        if ($connection)
        {
            $meta = stream_get_meta_data($connection);

            $header = new HttpHeaders(implode("\r\n", $meta['wrapper_data']));

            $result = stream_get_contents($connection);

            fclose($connection);

            return new Response($result, $header->getContentType());
        }
        throw new ConnectionException('SMS server is unavailable');
    }
}
