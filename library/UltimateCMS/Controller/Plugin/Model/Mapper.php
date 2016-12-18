<?php

class UltimateCMS_Controller_Plugin_Model_Mapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    protected function getDbTable($useViewTable = true)
    {
        if (null === $this->_dbTable) {
            if(null === $this->Model_DbTable) {
                throw new Exception('There is no Model_DbTable. You must define the Model_DbTable variable on the model in order to get the correct database table.');
            }
            $table = ($this->Model_DbView && $useViewTable !==  null) ? 
                    $this->Model_DbView : $this->Model_DbTable;
            $this->setDbTable($table);
        }
        return $this->_dbTable;
    }
 
    protected function MapperSave(array $data)
    {
        $currentModel = get_class($this);
        
        foreach($data as $index => $value){
            if(!property_exists($currentModel, $index)){
                //Delete key if not exist on the object
                unset($data[$index]);
            }
        }
        
        $id = (isset($data['id'])) ? $data['id'] : null;
        if (null === $id ) {
            unset($data['id']);
            $out = $this->getDbTable(false)->insert($data);
        } else {
            $this->getDbTable(false)->update($data, array('id = ?' => $id));
            $out = $id;
        }
        return $out;
    }
 
    protected function MapperGetByColumn($column = null, $value = null, $order = array('id DESC')){
        if(!$column || !$value)
            throw new Exception("Error -> MapperGetByColumn (The params are empty)");
        $currentModel = get_class($this);//Get the class that extends the mapper
        if(!property_exists ($currentModel, $column))
            throw new Exception("Error -> getByColumn (The Column '_{$column}' doesn't exist on model '{$currentModel}')");
        $result = $this->getDbTable()->select()->where("$column = ?", $value)->order($order);
        $resultSet = $this->getDbTable()->fetchAll($result);
        //Get the class that extends the mapper
        $currentModel = get_class($this);
        $entries = array();
        foreach ($resultSet as $row) {
            $data = $row->toArray();
            $entry = new $currentModel();
            $entry->setOptions($data);
            $entries[] = $entry;
        }
        return $entries;
    }
 
    protected function MapperDeleteByColumn($column = null, $value = null){
        if(!$column || !$value)
            throw new Exception("Error -> MapperDeleteByColumn (The params are empty)");
        //Get the class that extends the mapper
        $currentModel = get_class($this);
        if(!property_exists ($currentModel, $column))
                throw new Exception("Error -> MapperDeleteByColumn (The Column '_{$column}' doesn't exist on model '{$currentModel}')");
                
        $result = $this->getDbTable()->delete(array("$column=?" => $value));
        return $result;
    }
 
    protected function MapperSelect($sql = null, $params = null){
        if(!$sql)throw new Exception("Error -> MapperSelect (The sql is empty)");
        $db = Zend_Db_Table::getDefaultAdapter();
        $stmt = $db->query($sql, $params);
        $resultSet = $stmt->fetchAll();
        $currentModel = get_class($this);//Get the class that extends the mapper
        $entries = array();
        foreach ($resultSet as $data) {
            $entry = new $currentModel();
            $entry->setOptions($data);
            $entries[] = $entry;
        }
        return $entries;
 
    }
 
    /*
     * MapperGetBySQLCondition
     * @param $conditions must be an array with the WHERE conditions and values like the following:
     * array(
     *      'product_name = ?'=>'Wimax',
     *      'id <> ?'=>2
     * )
     * @param $OR if this is setted to true the conditions will be joined with an "OR" statement
     * ex: select * from example WHERE product=1 OR id=2
     */
    protected function MapperGetBySQLCondition(array $conditions, $use_OR = false, $order = array('id DESC')){
        if(!count($conditions))
            throw new Exception("Error -> MapperGetBySQLCondition (The conditions are empty)");
        $result = $this->getDbTable()->select();
        $first = true;
        foreach($conditions as $sql => $value){
            $where = ($use_OR && !$first) ? 'orWhere' : 'where';
            $result = $result->$where($sql, $value);
            $first = false;
        }
        
        $result->order($order);
        $resultSet = $this->getDbTable()->fetchAll($result);
        $currentModel = get_class($this);
        $entries = array();
        foreach ($resultSet as $row) {
            $data = $row->toArray();
            $entry = new $currentModel();
            $entry->setOptions($data);
            $entries[] = $entry;
        }
        return $entries;
 
    }
 
    protected function MapperGetById($id = null)
    {
        if (!$id)
            throw new Exception("Error -> MapperGetById (The ID is empty)");
        
        $result = $this->getDbTable()->find($id);
        $entries   = array();
        if (0 == count($result)) {
            return;
        }
        $row = $result->current()->toArray();
        $currentModel = get_class($this);//Get the class that extends the mapper
        $entry = new $currentModel();
        $entry->setOptions($row);
        $entries[] = $entry;
        return $entries;
    }
 
 
    protected function MapperFetchAll($order = array('id DESC'))
    {
        $resultSet = $this->getDbTable()->fetchAll(null,$order);
        $entries   = array();
        $currentModel = get_class($this);
        
        foreach ($resultSet as $row) {
            $data = $row->toArray();
            $entry = new $currentModel();
            $entry->setOptions($data);
            $entries[] = $entry;
        }
        return $entries;
    }
 
    public function toArray() {
        $class = get_class($this);
        $properties = get_class_vars ($class);
        $result = array();
        foreach($properties['viewColumns'] as $viewProperty){
            $properties[$viewProperty]=null;
        }
        unset($properties['viewColumns']);
        unset($properties['_dbTable']);
        foreach($properties as $key => $value){
            $result[$key] = $this->$key;
        }
        return $result;
    }
 
}