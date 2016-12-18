<?php

use UltimateCMS_Model_Language as Language;

class Model_Admin_Navigation_NavigationMenu extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_navigation_menu';
    
    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;
    
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    
    /**
     * Return all Navigation menu resources joined together
     * @param $parentOrChildRows specify to fetch just with
     * order number 0 or child elements with existing order number.
     * @return Zend_Db_Table_Rowset_Abstract 
     * The row results per the Zend_Db_Adapter fetch mode.
     */
    public function getNavigationResources($parentOrChildRows = 0) {
        $language = new Language();
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('cnm' => 'cms_navigation_menu'), 
                array('cnmid' => 'id', 'resource_id', 'type_id', 'parent_id', 'order_number', 'deleted'));
        
        $select->join('cms_page', 'cms_page.id = cnm.resource_id');
        $select->join('cms_page_content', 'cms_page_content.page_id = cms_page.id', 
                array('page_id', 'language_id', 'title'));
        
        $select->where('language_id = ?', $language->getFirst()->id);
        $select->where('cnm.deleted = ?', self::IS_ACTIVE);
        $select->where('cnm.parent_id = ?', $parentOrChildRows);
        $select->order('cnm.order_number ASC');
        return $this->fetchAll($select);
    }
}