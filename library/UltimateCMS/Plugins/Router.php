<?php

class UltimateCMS_Plugins_Router extends Zend_Controller_Plugin_Abstract {

    public function __construct($fc) {
        
        $link = new UltimateCMS_Collections_UrlRewrite_Link();

        // models
        $modelLanguage = new UltimateCMS_Model_Language();
        
        $ctrl = $fc->getRouter();
        
        // set route for language homepage
        $languages = $modelLanguage->getAll();
        if (count($languages) > 0) {
            foreach ($languages as $language) {
                $ctrl->addRoute("homepage_" . $language->id, new Zend_Controller_Router_Route("/" . $language->short, array(
                        "controller" => "index",
                        "action" => "index",
                        "language" => $language->id
                        )
                    )
                );
            }
        }
    }
}
