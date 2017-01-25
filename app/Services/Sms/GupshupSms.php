<?php

namespace Whatsloan\Services\Sms;

use GuzzleHttp\Client as Guzzle;

class GupshupSms implements Contract
{
    /**
     * @var Guzzle Client
     */
    protected $guzzle;

    /**
     * SMS Api Endpoint
     *
     * @var string
     */
    protected $endpoint = 'http://enterprise.smsgupshup.com/GatewayAPI/rest';

    /**
     * GupshupSms constructor.
     * @param Guzzle $guzzle
     */
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Send a SMS
     * @msg String needs to be approved in dashboard first.
     *
     * @param $number
     * @param $code
     * @return mixed
     */
    public function send($number, $code)
    {
        $response = $this->guzzle->request('GET', $this->endpoint, [
            'query' => [
                'method'      => 'SendMessage',
                'send_to'     => $number,
                'msg'         => "Enter the One Time Password (OTP) to complete the process. Your OTP is $code",
                'msg_type'    => 'TEXT',
                'userid'      => config('services.gupshup.userid'),
                'auth_scheme' => 'plain',
                'password'    => config('services.gupshup.password'),
                'v'           => '1.1',
                'format'      => 'text',
            ]
        ]);

        return $response;
    }

    /**
     * Send a SMS
     *
     * @param $number
     * @param $message
     * @return mixed
     */
    public function sendMessage($number, $message)
    {
        $response = $this->guzzle->request('GET', $this->endpoint, [
            'query' => [
                'method'      => 'SendMessage',
                'send_to'     => $number,
                'msg'         => $message,
                'msg_type'    => 'TEXT',
                'userid'      => config('services.gupshup.userid'),
                'auth_scheme' => 'plain',
                'password'    => config('services.gupshup.password'),
                'v'           => '1.1',
                'format'      => 'text',
            ]
        ]);

        return $response;
    }
}