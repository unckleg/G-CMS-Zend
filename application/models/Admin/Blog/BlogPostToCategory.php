<?php

use UltimateCMS_Model_Language as Language;

class Model_Admin_Blog_BlogPostToCategory extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_postToCategory_to_category';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    /**
     * Return array-object of all postToCategorys from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $postToCategorys = $this->fetchAll($select);
        return $postToCategorys;
    }

    /**
     * @param array $postToCategoryData
     * @return object of newly created postToCategory
     */
    public function addPostToCategory($postToCategoryData) {
        $postToCategory = $this->insert($postToCategoryData);
        return $postToCategory;
    }

    /**
     * @param int $postToCategoryId
     * @param array $postToCategoryDate
     */
    public function editPostToCategory($postToCategoryId, $postToCategoryDate) {
        if (isset($postToCategoryData['id'])) {
            unset($postToCategoryData['id']);
        }
        $this->update($postToCategoryDate, 'id = ' . $postToCategoryId);
    }

    /**
     * If postToCategory with given id is found do soft-delete else throw Exception
     * @param int $postToCategoryId
     * @return Row Delete|Exception
     */
    public function deletePostToCategory($postToCategoryId) {
        $select = $this->select();
        $select->where('id = (?)', $postToCategoryId);
        $row = $this->fetchRow($select);

        if ($row) {
            $this->update(array(
                'deleted' => self::IS_DELETED
            ), 'id = ' . $postToCategoryId);
        } else
            return new Exception('No postToCategory found for given id;');
    }
}