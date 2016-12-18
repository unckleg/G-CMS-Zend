<?php

class UltimateCMS_Plugins_Language extends Zend_Controller_Plugin_Abstract 
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $modelLanguage = new UltimateCMS_Model_Language();
        $languageId = $request->getParam('language');

        if (isset($languageId) && $languageId != ""  && $languageId != NULL) {
            $language = $modelLanguage->find($languageId)->current();
        } else {
            $language = $modelLanguage->getFirst();
        }

        Zend_Registry::set('language', $language);
    }
}