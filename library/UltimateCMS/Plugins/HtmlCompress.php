<?php

class UltimateCMS_Plugins_HtmlCompress extends Zend_Controller_Plugin_Abstract
{
    /*public function dispatchLoopShutdown()
    {
        $response = Zend_Controller_Front::getInstance()->getResponse();
        $responseBodyHtml = implode('', $response->getBody(true));

        $htmlCompact = new UltimateCMS_Collections_Markup_HtmlCompact();
        $compressedHtml = $htmlCompact->htmlCompact($responseBodyHtml);

        $response->setBody($compressedHtml);
    }*/
}