<?php

class Admin_SessionController extends Zend_Controller_Action 
{
    public function init() {
        Zend_Layout::getMvcInstance()->disableLayout();
    }
    
    public function indexAction() {
        
        // check if user is logged-in
        // if is logged-in redirect to network_home
        if (Zend_Auth::getInstance()->hasIdentity()) {
            //set system message
            $flashMessenger = $this->getHelper('FlashMessenger');
            $flashMessenger->addMessage('You are already logged in!', 'success');
            $redirector = $this->getHelper('Redirector');
            $redirector instanceof Zend_Controller_Action_Helper_Redirector;
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_dashboard',
                        'action' => 'index'
                     ), 'default', true);
        } else {
            // user is not logged-in
            // redirect to login-page
            $redirector = $this->getHelper('Redirector');
            $redirector instanceof Zend_Controller_Action_Helper_Redirector;
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_session',
                        'action' => 'login'
                     ), 'default', true);
        }	
    }
	
    public function loginAction() {
       
        // initialisation of form class
        $loginForm = new Form_Login();
        // get elements from POST 
        $request = $this->getRequest();
        $request instanceof Zend_Controller_Request_Http;
        // initialisation of flashMessenger
        $flashMessenger = $this->getHelper('FlashMessenger');
        // message types
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors')
        );
        
        // login-form validation and proccessing
        if ($request->isPost() && $request->getPost('task') === 'login') {
            
            if ($loginForm->isValid($request->getPost())) {
                
                // initialisation of Zend_Db_Table class
                $authAdapter = new Zend_Auth_Adapter_DbTable();
                $authAdapter->setTableName('cms_users')
                            ->setIdentityColumn('username')
                            ->setCredentialColumn('password')
                            ->setCredentialTreatment('MD5(?)');

                $authAdapter->setIdentity($loginForm->getValue('username'));
                $authAdapter->setCredential($loginForm->getValue('password'));

                $auth = Zend_Auth::getInstance();

                $result = $auth->authenticate($authAdapter);

                // if user is successfully logged-in
                if ($result->isValid()) {
                    // sending row from db table users as identification
                    // that user is logged-in and saving to
                    // Zend Session Storage
                    $user = (array) $authAdapter->getResultRowObject();
                    $auth->getStorage()->write($user);
                    //set system message
                    $flashMessenger = $this->getHelper('FlashMessenger');
                    $flashMessenger->addMessage('You are successfully logged in!', 'success');
                    // redirect to 
                    $redirector = $this->getHelper('Redirector');
                    $redirector instanceof Zend_Controller_Action_Helper_Redirector;
                    $redirector->setExit(true)
                            ->gotoRoute(array(
                                    'controller' => 'admin_dashboard',
                                    'action' => 'index'
                                 ), 'default', true);
                } else {
                    // logged-in credentials are incorrect
                    // show the message and dont let user
                    // to pass to dashboard
                    $systemMessages['errors'][] = 'Username or password is wrong, please try again but dont bruteforce us.';
                }
                
            } else {
                // user tried to access without username and password
                // show the message and dont let user to pass to dashboard
                $systemMessages['errors'][] = 'Username and Password fields are required';
            }
        } 
        // sending systemMessages method as variable 
        // to View logic
        $this->view->systemMessages = $systemMessages;
    }

    public function logoutAction() {
        
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $flashMessenger = $this->getHelper('FlashMessenger');

        $flashMessenger->addMessage('You have been logged out', 'success');

        // Ovde ide redirect na login stranu
        $redirector = $this->getHelper('Redirector');
        $redirector instanceof Zend_Controller_Action_Helper_Redirector;

        $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'admin_session',
                    'action' => 'login'
                 ), 'default', true);
    }
}