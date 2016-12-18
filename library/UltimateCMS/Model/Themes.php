<?php

class UltimateCMS_Model_Themes extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'themes';

    const THEME_ACTIVE = 1;
    
    /**
     * @param int $id
     * @return null|object with keys as themes table columns or NULL if not found
     */
    public function getThemeById($themeId) {
        $select = $this->select();
        $select->where('id = ?', $themeId);
        
        $row = $this->fetchRow($select);

        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else {
            return;
        }
    }

    /**
     * @return object
     */
    public static function getActiveTheme() {
        $mdl = new self();
        $select = $mdl->select();
        
        $select->where('theme_status = (?)', self::THEME_ACTIVE);
        
        return $mdl->fetchRow($select);
    }
    
    public static function getActiveThemeScreenshot() {
        $mdl = new self();
        $select = $mdl->select();
        $themeId = self::getActiveTheme()->id;
        if (!is_null($themeId)) {
            $select->where('id = (?)', $themeId);
            return $mdl->fetchRow($select)->theme_screenshot;
        } else {
            return new Exception('No id provided for theme.');
        }
    }
    

    public function getAll() {
        $mdl = new self();
        $select = $mdl->select();

        return $mdl->fetchAll($select);
    }
}