<?php

class HttpRequest
{
    public static function getResponse($url, $method = Zend_Http_Client::GET, $timeout = 300)
    {
        try {
            $request = new Zend_Http_Client();
            $request->setConfig(array('timeout' => $timeout));
            $request->setUri($url);
            $request->setMethod($method);
            $content = $request->request()->getBody();
            return $content;
        } catch (Exception $ex) {
            // Could not connect to $url
            return null;
        }
    }
}