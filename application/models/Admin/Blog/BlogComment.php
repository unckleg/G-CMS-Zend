<?php


class Model_Admin_Blog_BlogComment extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_comment';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_APPROVED = 1;
    const STATUS_DENIED = 0;
    const COMMENT_READED = 1;
    const COMMENT_PENDING = 0;

    /**
     * Return array-object of all comments from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $comments = $this->fetchAll($select);
        return $comments;
    }
    
    /**
     * If comment exist return row-object else return null
     * @param int $commentId
     * @return Object\Zend_Db_Table_Row
     */
    public function getCommentById($commentId) {
        $select = $this->select();
        $select->where('id = (?)', $commentId);

        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else
            return;
    }
    
    /**
     * @param array $commentData
     * @return object of newly created comment
     */
    public function addComment($commentData) {
        $comment = $this->insert($commentData);
        return $comment;
    }

    /**
     * @param int $commentId
     * @param array $commentDate
     */
    public function editComment($commentId, $commentDate) {
        if (isset($commentData['id'])) {
            unset($commentData['id']);
        }
        $this->update($commentDate, 'id = ' . $commentId);
    }

    /**
     * If comment with given id is found do soft-delete else throw Exception
     * @param int $commentId
     * @return Row Delete|Exception
     */
    public function deleteComment($commentId) {
        $select = $this->select();
        $select->where('id = (?)', $commentId);
        $row = $this->fetchRow($select);

        if ($row) {
            $this->update(array(
                'deleted' => self::IS_DELETED
            ), 'id = ' . $commentId);
        } else
            return new Exception('No comment found for given id;');
    }
}