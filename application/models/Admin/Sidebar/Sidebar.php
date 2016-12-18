<?php

use UltimateCMS_Model_ORM as ORM,
    Model_Admin_Sidebar_SidebarLabel as SidebarLabel,
    Model_Admin_Sidebar_SidebarContent as SidebarContent,
    Model_Admin_Sidebar_SidebarLabelContent as SidebarLabelContent,
    Model_Admin_Sidebar_SidebarSeparator as SidebarSeparator,
    Model_Admin_Sidebar_SidebarSeparatorContent as SidebarSeparatorContent;

class Model_Admin_Sidebar_Sidebar extends Zend_Db_Table_Abstract 
{
    // table name
    protected $_name = 'cms_sidebar';
    protected $_language;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    const SIDEBAR_STATUS = 1;

    public function __construct($config = array())
    {
        $language = new UltimateCMS_Model_Language();
        $this->_language = $language->getFirst()['id']+1;

        parent::__construct($config);
    }

    /**
     * @param int $sidebarId
     * @return null|object with keys as Sidebar table columns or NULL if not found
     */
    public function getSidebarById($sidebarId) {
        $select = $this->select();
        $select->where('id = ?', $sidebarId);

        $row = $this->fetchRow($select);

        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else {
            return;
        }
    }

    public function getSidebarResources() {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('csl' => 'cms_sidebar_label'),
                array('label_order_number' => 'order_number', 'icon'))
               ->join(array('cslc' => 'cms_sidebar_label_content'),
                   'cslc.sidebar_label_id = csl.id', array('cslctitle' => 'title'))
               ->join('cms_sidebar', 'cms_sidebar.label_id = csl.id')
               ->join(array('cmsacl' => 'cms_acl'), 'cmsacl.id = cms_sidebar.acl_id')
               ->join(array('csc' => 'cms_sidebar_content'),
                   'csc.sidebar_id = cms_sidebar.id')
                ->where('cslc.language_id = ?', 1)
                ->where('csc.language_id = ?', 1);
        return $this->_getModifiedSidebarArray($this->fetchAll($select)->toArray());
    }

    public function _getModifiedSidebarArray(array $resources) {
        $parameters = array();
        $temp = array();
        if (is_array($resources)) {
            foreach ($resources as $resource) {
                $parameters[$resource['cslctitle']]['label'] = array(
                    'title' => $resource['cslctitle'],
                    'icon' => $resource['icon'],
                    'order_number' => $resource['label_order_number'],
                );

                $temp[$resource['cslctitle']][] = array(
                    'title' => $resource['title'],
                    'separator_id' => $resource['separator_id'],
                    'order_number' => $resource['order_number'],
                    'controller' => $resource['controller'],
                    'action' => $resource['action']
                );
                $parameters[$resource['cslctitle']]['label']['data'] = $temp[$resource['cslctitle']];

            }

        }
        return $parameters;
    }

    public function getAll() {
        $mdl = new self();
        $select = $mdl->select();

        $select->order('order_number ASC');

        return $mdl->fetchAll($select);
    }
}



