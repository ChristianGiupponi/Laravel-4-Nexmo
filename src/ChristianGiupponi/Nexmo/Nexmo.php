<?php

namespace ChristianGiupponi\Nexmo;

use GuzzleHttp\Client;

class Nexmo {

    protected $apiKey;
    protected $apiSecret;
    public $nexmoUrl = "https://rest.nexmo.com/account/";
    public $nexmoPostUrl = "https://rest.nexmo.com/sms/json";

    /**
     * getBalance
     * this function will return the balance of you Nexmo account
     *
     * @return json
     */
    public function getBalance()
    {
        //Set the base api url
        $base_url = "get-balance/";

        //Get result from api
        return $this->get_request( $base_url );

    }

    /**
     * pricing
     * Get the price of a given country.
     * It returns also the available carrier name with price.
     *
     * @param string $countryOrPrefix
     * @return json
     */
    public function pricing( $countryOrPrefix = '' )
    {
        //check if the request is fo a specific country or prefix
        if( trim( $countryOrPrefix ) == '' )
        {
            return json_encode( [
                'code'   => '400',
                'reason' => 'Code or Prefix required',
                'body'   => ''
            ] );
        }

        $base_url = 'get-pricing/outbound/';
        $params   = '/' . $countryOrPrefix;

        return $this->get_request( $base_url, $params );

    }

    /**
     * Send SMS
     *
     * This function will send a post request to Nexmo website to send a new sms
     * To see all the options available please visite nexmo website:
     *      https://docs.nexmo.com/index.php/sms-api/send-message
     * The option must be formed like: array( 'foo' => 'bar' )
     *
     * @param string $from
     * @param string $to
     * @param string $text
     * @param array $options
     * @return json
     */
    public function sendSMS( $from = '', $to = '', $text = '', $options = [ ] )
    {
        if( trim( $from ) == '' || trim( $to ) == '' || trim( $text ) == '' )
        {
            return json_encode( [
                'code'   => '400',
                'reason' => 'Missing parameter, Form - To and Text are required.',
                'body'   => ''
            ] );
        }

        $required = [
            'from' => $from,
            'to'   => $to,
            'text' => htmlentities( $text )
        ];

        return $this->post_request( $required, $options );

    }

    /**
     * api_request
     * This function will make the api call using GuzzleHttp
     *
     * @param $method
     * @param $base_url
     * @param string $params
     * @return json
     */
    public function get_request( $base_url, $params = '' )
    {
        $this->apiKey    = \Config::get( 'nexmo::nexmo_api_key' );
        $this->apiSecret = \Config::get( 'nexmo::nexmo_api_secret' );

        //Create the url
        $url = $this->nexmoUrl . $base_url . $this->apiKey . '/' . $this->apiSecret . $params;

        //Initialize a new client for api request
        $client = new Client();

        //Send the request
        $response = $client->get( $url, [
            'headers' => [ 'Accept' => 'application/json' ]
        ] );

        //Get the result
        $code   = $response->getStatusCode();
        $reason = $response->getReasonPhrase();
        $body   = $response->json();

        //Format the result
        return json_encode( [
            'code'   => $code,
            'reason' => $reason,
            'body'   => $body
        ] );
    }

    public function post_request( $requried = [ ], $optional = [ ] )
    {
        //Get api from config file
        $this->apiKey    = \Config::get( 'nexmo::nexmo_api_key' );
        $this->apiSecret = \Config::get( 'nexmo::nexmo_api_secret' );

        //Create uri params with the required file
        $required_url = http_build_query( $requried );

        //If there are any optional fields let's add them
        $optional_url = ( count( $optional ) > 0 ) ? '&' . http_build_query( $optional ) : '';

        //This is the final url
        $url = $this->nexmoPostUrl . "?api_key=" . $this->apiKey . '&api_secret=' . $this->apiSecret . '&' . $required_url . $optional_url;

        //Initialize a new client for api request
        $client = new Client();

        //Make POST request
        $response = $client->post( $url );

        //Get the result
        $code   = $response->getStatusCode();
        $reason = $response->getReasonPhrase();
        $body   = $response->json();

        //Format the result
        return json_encode( [
            'code'   => $code,
            'reason' => $reason,
            'body'   => $body
        ] );

    }
} 
