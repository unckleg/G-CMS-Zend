<?php

use UltimateCMS_Model_Language as Language;

class Model_Admin_Blog_BlogPost extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_post';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    /**
     * Return array-object of all posts from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $posts = $this->fetchAll($select);
        return $posts;
    }

    /**
     * @param array $postData
     * @return object of newly created post
     */
    public function addPost($postData) {
        $post = $this->insert($postData);
        return $post;
    }

    /**
     * @param int $postId
     * @param array $postDate
     */
    public function editPost($postId, $postDate) {
        if (isset($postData['id'])) {
            unset($postData['id']);
        }
        $this->update($postDate, 'id = ' . $postId);
    }

    /**
     * If post with given id is found do soft-delete else throw Exception
     * @param int $postId
     * @return Row Delete|Exception
     */
    public function deletePost($postId) {
        $select = $this->select();
        $select->where('id = (?)', $postId);
        $row = $this->fetchRow($select);

        if ($row) {
            $this->update(array(
                'deleted' => self::IS_DELETED
            ), 'id = ' . $postId);
        } else
            return new Exception('No post found for given id;');
    }
}