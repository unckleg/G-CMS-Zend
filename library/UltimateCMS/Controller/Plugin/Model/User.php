<?php

class UltimateCMS_Controller_Plugin_Model_User extends UltimateCMS_Controller_Plugin_Model_Mapper
{
    protected $Model_DbTable = 'UltimateCMS_Controller_Plugin_Model_DbTable_User';
    protected $Model_DbView;
    protected $id;
    protected $role_id;
    protected $login;
    protected $password;
    protected $salt;
    protected $viewColumns = array();
 
    public function __set($name, $value)
    {
        if (('mapper' == $name) || !property_exists ($this, $name)) {
            throw new Exception('Error __set() function, Invalid  property');
        }
        $this->$name = (string) $value;
    }
 
    public function __get($name)
    {
        if (('mapper' == $name) || !property_exists($this, $name)) {
            if(array_key_exists ($name,$this->viewColumns)){
                return $this->viewColumns[$name];
            }
            throw new Exception("Error __get() function, Invalid  property '$name'");
        }
        return $this->$name;
    }
 
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key= (string) $value;
            }elseif(in_array($key, $this->viewColumns)){//Check if the property is a view
                $this->viewColumns[$key] = (string) $value;
                unset($this->viewColumns[array_search($key, $this->viewColumns)]);
            }
        }
        return $this;
    }
 
    public static function getByColumn($column = null, $value = null, $order=null){
        $mapper = new self();
        $out = $mapper->MapperGetByColumn($column, $value, $order);
        return $out;
    }
    
    public static function getBySQLCondition(array $conditions, $is_OR=false, $order = null ){
        $mapper = new self();
        $out = $mapper->MapperGetBySQLCondition($conditions, $is_OR, $order);
        return $out;
    }
    
    public static function getAll($order = null){
        $mapper = new self();
        $out = $mapper->MapperFetchAll($order);
        return $out;
    }
 
    public static function select($sql = null, $params = null){
        $mapper = new self();
        $out = $mapper->MapperSelect($sql, $params);
        return $out;
    }
    
    public static function save(array $data){
        $mapper = new self();
        $out = $mapper->MapperSave($data);
        return $out;
    }
 
    public static function getById($id = null){
        $mapper = new self();
        $out = $mapper->MapperGetById($id);
        return $out;
    }
    
    public static function getCurrentUser() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()){
            return 0;
        }
        $id = $auth->getIdentity()->id;
        $user = Application_Model_User::getById($id);
        return (count($user)) ? $user[0]->toArray() : 0;
    }
 
    public function getViewProperty($column = null){
        return (in_array($column, $this->viewColumns))? $column : null;
    }
 
    /**
    * Check if the user is allowed to access the provided Controller->action
    **/
    public static function isAllowed($controller, $action) {
        //Get Current user role
        $auth = Zend_Auth::getInstance();
        $role = Application_Model_Role::getById($auth->getIdentity()->role_id);
        $acl = Zend_Registry::get('acl');
        return ($acl->isAllowed($role[0]->role, $controller, $action)) ? true : false;  
    }
}