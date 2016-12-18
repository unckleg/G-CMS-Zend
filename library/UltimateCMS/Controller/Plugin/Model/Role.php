<?php

class UltimateCMS_Controller_Plugin_Model_Role extends UltimateCMS_Controller_Plugin_Model_Mapper
{
    protected $Model_DbTable = 'UltimateCMS_Controller_Plugin_Model_DbTable_Role';
    protected $Model_DbView;
    protected $id;
    protected $role;
    protected $viewColumns = array();
 
    const GUEST = 1;
    const MODERATOR = 2;
    const ADMIN = 3;
    const SUPERADMIN = 4;
    
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
            if(array_key_exists ($name, $this->viewColumns)){
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
            } elseif(in_array($key, $this->viewColumns)) { //Check if the property is a view
                $this->viewColumns[$key] = (string)$value;
                unset($this->viewColumns[array_search($key, $this->viewColumns)]);
            }
        }
        return $this;
    }
 
 
    public static function getByColumn($column = null, $value = null, $order = null) {
        $mapper = new self();
        $out = $mapper->MapperGetByColumn($column, $value, $order);
        return $out;
    }
    
    public static function getBySQLCondition(array $conditions, $is_OR = false, $order = null) {
        $mapper = new self();
        $out = $mapper->MapperGetBySQLCondition($conditions, $is_OR, $order);
        return $out;
    }
    public static function getAll($order = null) {
        $mapper = new self();
        $out = $mapper->MapperFetchAll($order);
        return $out;
    }
 
    public static function select($sql = null, $params = null) {
        $mapper = new self();
        $out = $mapper->MapperSelect($sql,$params);
        return $out;
    }
    
    public static function save(array $data) {
        $mapper = new self();
        $out = $mapper->MapperSave($data);
        return $out;
    }
 
    public static function getById($id = null) {
        $mapper = new self();
        $out = $mapper->MapperGetById($id);
        return $out;
    }
    
    public static function getRoleFromSessionOrSetDefault() {
        $mapper = new self();
        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $out = $mapper->getById($user['role_id']);
        } else {
            $role_id = self::GUEST; 
            $out = $mapper->getById($role_id);
        }
        return $out;
    } 
 
}