<?php
namespace BulkGate\Extensions\IO;

use BulkGate;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class cUrl extends BulkGate\Extensions\SmartObject implements IConnection
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
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request->getUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT  => true,
            CURLOPT_POSTFIELDS => $request->getData(),
            CURLOPT_HTTPHEADER => array(
                'Content-type: ' . $request->getContentType(),
                'X-BulkGate-Application-ID: ' . (string) $this->application_id,
                'X-BulkGate-Application-Token: ' . (string) $this->application_token,
                'X-BulkGate-Application-Url: ' . (string) $this->application_url,
                'X-BulkGate-Application-Product: '. (string) $this->application_product
            ),
        ));

        /*curl_setopt($curl, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($curl, CURLOPT_NOSIGNAL, 1);*/

        $request = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = new HttpHeaders(substr($request, 0, $header_size));

        $json = substr($request, $header_size);
        //var_dump($request);die;

        if($json)
        {
            curl_close($curl);
            return new Response($json, $header->getContentType());
        }

        $error = curl_error($curl);

        curl_close($curl);
        throw new ConnectionException('SMS server is unavailable - '. $error);
    }
}