<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected $_appNamespace = 'UltimateCMS';
    
    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'resourceTypes' => array(
                'form' => array(
                    'path' => 'forms/',
                    'namespace' => 'Form_'
                ),
                'model' => array(
                    'path' => 'models/',
                    'namespace' => 'Model_'
                ),
            ),
            'basePath' => APPLICATION_PATH
            ));
        return $moduleLoader;
    }

    protected function _initConfig() {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }

    protected function _initDatabases() {
        $this->bootstrap('db');
    }
    
    protected function getThemeByControllerName() {
        $router = new Zend_Controller_Router_Rewrite();
        $request = new Zend_Controller_Request_Http();
        $router->route($request);
        $controllerName = $request->getControllerName();

        if(!preg_match('/^admin_/', $controllerName)) {
            return TRUE;
        } elseif(preg_match('/^admin_/', $controllerName)) {
            return FALSE;
        } else {
            return 'default';
        }
    }
    
    protected function _initLayout() {
        $requestStatus = $this->getThemeByControllerName();
        
        if($requestStatus) {

            $activeTheme = UltimateCMS_Model_Themes::getActiveTheme();
            if ($activeTheme !== NULL) {
                $path = APP_PUBLIC . '/themes/' . $activeTheme->theme_folder . '/templates';
                Zend_Registry::set('theme', $activeTheme);
                $layout = Zend_Layout::startMvc()
                    ->setLayout('layout')
                    ->setLayoutPath($path)
                    ->setContentKey('content'); 
            }
           
        } elseif(!$requestStatus) {
            $layout = Zend_Layout::startMvc()
                ->setLayout('backend')
                ->setLayoutPath(APPLICATION_PATH . "/layouts/scripts")
                ->setContentKey('content'); ;
        } else {
            $layout = Zend_Layout::startMvc()
                ->setLayout('layout')
                ->setLayoutPath(APPLICATION_PATH . "/layouts/scripts")
                ->setContentKey('content'); ;
        }
        
    }
    
    protected function _initView() {
        $this->view = new Zend_View();
        $requestStatus = $this->getThemeByControllerName();

        if ($requestStatus) {
            $activeTheme = UltimateCMS_Model_Themes::getActiveTheme();
            $path = APP_PUBLIC . '/themes/' . $activeTheme->theme_folder . '/templates';
            $this->view->addScriptPath($path);
            $this->view->setScriptPath($path);
            $this->view->headTitle('Web site title')->setSeparator(' - ');
        } else {
            $this->view->headTitle('Ultimate CMS')->setSeparator(' - ');
        }
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($this->view);
        $viewRenderer->view->addHelperPath(APPLICATION_PATH . '/../library/UltimateCMS/Helpers/View', '')
                            ->addHelperPath(APPLICATION_PATH . '/../library/UltimateCMS/Helpers/Action', '')
                            ->addHelperPath(APPLICATION_PATH . '/../library/UltimateCMS/Helpers/View/Modules', '');

        return $this->view;
    }
    
    protected function _initTranslate() {
        
        $modelLanguage = new UltimateCMS_Model_Language();
        $languages = $modelLanguage->getAll();
        
        if (count($languages) > 0) {
            $i = 0;
            foreach ($languages as $value) {
                if ($i == 0) {
                    $translate = new Zend_Translate(
                        array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/translate/languages/' . $value->short . '.php',
                        'locale' => $value->short
                        )
                    );
                } else {
                    $translate->addTranslation(
                        array(
                            'adapter' => 'array',
                            'content' => APPLICATION_PATH . '/translate/languages/' . $value->short . '.php',
                            'locale' => $value->short
                        )
                    );
                }
                $i++;
            }

            $i = 0;
            foreach ($languages as $value) {
                if ($i == 0) {
                    $translate->setLocale($value->short);
                }
                $i++;
            }
            
            Zend_Registry::set('Zend_Translate', $translate);
        }
    }

    protected function _initPlugins() {
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new UltimateCMS_Plugins_Router(Zend_Controller_Front::getInstance()));
        $fc->registerPlugin(new UltimateCMS_Plugins_Admin());
        $fc->registerPlugin(new UltimateCMS_Plugins_CsrfProtect());
        $fc->registerPlugin(new UltimateCMS_Plugins_HtmlCompress());
    }
}
