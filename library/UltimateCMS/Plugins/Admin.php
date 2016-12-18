<?php

class UltimateCMS_Plugins_Admin extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {

        $controllerName = $request->getControllerName();

        if(preg_match('/^admin_/', $controllerName)) {

            if (!Zend_Auth::getInstance()->hasIdentity() && $controllerName != 'admin_session') {

                $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flashMessenger->addMessage('You must login to grant access to dashboard', 'errors');

                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_session',
                            'action' => 'login'
                         ), 'default', true);
            }
        }
    }
}