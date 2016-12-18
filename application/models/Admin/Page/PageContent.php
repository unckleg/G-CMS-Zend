<?php

class Model_Admin_Page_PageContent extends Zend_Db_Table_Abstract 
{
    // table name
    protected $_name = 'cms_page_content';
    
    /**
     * Return array-object of all pages-content from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $pagesContent = $this->fetchAll($select);
        return $pagesContent;
    }
    
    /**
     * If page-content exist return row-object else return null
     * @param int $pageContentId
     * @return Object\Zend_Db_Table_Row
     */
    public function getPageContentById($pageContentId) {
        $select = $this->select();
        $select->where('id = (?)', $pageContentId);
        
        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else 
            return;
    }
    
    /**
     * If content exist return row else null
     * @param int $pageId
     * @param int $langId
     * @return Object\Zend_Db_Table_Row
     */
    public function getContentByPageAndLang($pageId, $langId) {
        $select = $this->select();
        $select->where('page_id = ?', $pageId);
        $select->where('language_id = ?', $langId);

        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else {
            return;
        }
    }
    
    /**
     * @param array $pageContentData
     * @return object of newly created page-content
     */
    public function addPageContent($pageContentData) {
        $pageContent = $this->insert($pageContentData);
        return $pageContent;
    }
    
    /**
     * Updating page content where parent page_id and lang_id Row is
     * @param int $pageId
     * @param int $langId
     * @param array $pageContentData
     */
    public function editPageContent($pageId, $langId, $pageContentData) {
        if (isset($pageContentData['id'])) {
            unset($pageContentData['id']);
        }
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('page_id = ?', $pageId);
        $where[] = $this->getAdapter()->quoteInto('language_id = ?', $langId);
        $this->update($pageContentData, $where);
    }
    
}