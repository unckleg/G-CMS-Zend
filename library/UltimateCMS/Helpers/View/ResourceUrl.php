<?php

class ResourceUrl extends Zend_View_Helper_Abstract
{
    protected $_actionUrl;
    protected $_indexUrl;

    public function resourceUrl()
    {
        return $this;
    }

    public function action($action)
    {
        $front = Zend_Controller_Front::getInstance();
        $ctrl = $front->getRequest()->getControllerName();

        $this->_actionUrl = $this->view->url(array(
            'controller' => $ctrl,
            'action' =>  $action,
          ),'default', true);

        return $this->_actionUrl;
    }

    public function index($index)
    {
        $front = Zend_Controller_Front::getInstance();
        $ctrl = $front->getRequest()->getControllerName();

        $this->_indexUrl = $this->view->url(array(
            'controller' => $ctrl,
            'action' =>  $index,
          ),'default', true);

        return $this->_indexUrl;
    }

}