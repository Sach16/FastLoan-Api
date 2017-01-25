<?php

namespace Whatsloan\Services\Sms;

interface Contract
{
    /**
     * Send a SMS
     *
     * @param $number
     * @param $code
     * @return mixed
     */
    public function send($number, $code);

    /**
     * Send a SMS
     *
     * @param $number
     * @param $message
     * @return mixed
     */
    public function sendMessage($number, $message);
}