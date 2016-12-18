<?php

use UltimateCMS_Model_Language as Language;

class Model_Admin_Page_Page extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_page';
    
    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;
    
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    
    /**
     * Return array-object of all pages from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $pages = $this->fetchAll($select);
        return $pages;
    }
    
    /**
     * Return array-object of all pages with page content joined
     * @return array
     */
    public function getAllWithContent() {
        $language = new Language();
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('cms_page', array('id', 'status', 'deleted'));
        $select->join('cms_page_content', 'cms_page_content.page_id = cms_page.id');
        $select->where('language_id = ?',$language->getFirst()->id)
                ->where('deleted = ?', self::IS_ACTIVE);
        return $this->fetchAll($select)->toArray();
    }
    
    /**
     * Return array-object of all pages with page content joined
     * @return array
     */
    public function getAllWithContentAndNavigation() {
        $language = new Language();
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('cms_page', array('id', 'navigation_id', 'status', 'deleted'));
        $select->join('cms_page_content', 'cms_page_content.page_id = cms_page.id');
        $select->join('cms_navigation_menus', 'cms_navigation_menus.id = cms_page.navigation_id');
        $select->where('language_id = ?',$language->getFirst()->id);
        $select->where("deleted = ?", self::IS_ACTIVE);
        $select->order('order_number ASC');
        return $this->fetchAll($select);
    }


    /**
     * If page exist return row-object else return null
     * @param int $pageId
     * @return Object\Zend_Db_Table_Row
     */
    public function getPageById($pageId) {
        $select = $this->select();
        $select->where('id = (?)', $pageId);
        
        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else 
            return;
    }
    
    /**
     * @param array $pageData
     * @return object of newly created page
     */
    public function addPage($pageData) {
        $page = $this->insert($pageData);
        return $page;
    }
    
    /**
     * @param int $pageId
     * @param array $pageDate
     */
    public function editPage($pageId, $pageDate) {
        if (isset($pageData['id'])) {
            unset($pageData['id']);
        }
        $this->update($pageDate, 'id = ' . $pageId);
    }
    
    /**
     * If page with given id is found do soft-delete else throw Exception
     * @param int $pageId
     * @return Row Delete|Exception
     */
    public function deletePage($pageId) {
        $select = $this->select();
        $select->where('id = (?)', $pageId);
        $row = $this->fetchRow($select);
        
        if ($row) {
            $this->update(array(
                'deleted' => self::IS_DELETED
            ), 'id = ' . $pageId);
        } else 
            return new Exception('No page found for given id;');
    }
}