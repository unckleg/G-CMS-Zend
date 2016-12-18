<?php 

class Form_Login extends Zend_Form 
{
   // creating element for view logic
   // the constructor value username is field from
   // db: ultimate-cms, table: cms_users
   public function init() {
        $username = new Zend_Form_Element_Text('username');
        $username->addFilter('StringTrim')
                ->addFilter('StringToLower')
                ->setRequired(true);
        $this->addElement($username);

        $password = new Zend_Form_Element_Password('password');
        $password->setRequired(true);
        $this->addElement($password);
    }
}