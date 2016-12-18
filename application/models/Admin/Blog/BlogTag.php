<?php


class Model_Admin_Blog_BlogTag extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_tag';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    /**
     * Return array-object of all tags from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $tags = $this->fetchAll($select);
        return $tags;
    }

    /**
     * If tag exist return row-object else return null
     * @param int $tagId
     * @return Object\Zend_Db_Table_Row
     */
    public function getTagById($tagId) {
        $select = $this->select();
        $select->where('id = (?)', $tagId);

        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else
            return;
    }

    /**
     * @param array $tagData
     * @return object of newly created tag
     */
    public function addTag($tagData) {
        $tag = $this->insert($tagData);
        return $tag;
    }

    /**
     * @param int $tagId
     * @param array $tagDate
     */
    public function editTag($tagId, $tagData) {
        if (isset($tagData['id'])) {
            unset($tagData['id']);
        }
        $this->update($tagData, 'id = ' . $tagId);
    }

    /**
     * If tag with given id is found do soft-delete else throw Exception
     * @param int $tagId
     * @return Row Delete|Exception
     */
    public function deleteTag($tagId) {
        $select = $this->select();
        $select->where('id = (?)', $tagId);
        $row = $this->fetchRow($select);

        if ($row) {
            $this->update(array(
                'deleted' => self::IS_DELETED
            ), 'id = ' . $tagId);
        } else
            return new Exception('No tag found for given id;');
    }
}