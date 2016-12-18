<?php

class Model_Admin_Sidebar_SidebarContent extends Zend_Db_Table_Abstract 
{
    // table name
    protected $_name = 'cms_sidebar_content';
    
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    
    public function getSidebarContentBySidebarId($sidebarId) {
        $select = $this->select();
        $select->where('sidebar_id = (?)', $sidebarId);
        
        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else 
            return;
    }
    
    
}