<?php

use UltimateCMS_Model_Language as Language,
    Zend_Db_Table_Abstract as ZendDbAbstract;

class Model_Admin_Blog_BlogPost extends Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_post';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    // status constatns
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    const STATUS_ONHOLD = 2;
    const STATUS_FOR_MODERATOR = 3;

    // comments enabled
    const COMMENT_ENABLED = 1;
    const COMMENT_DISABLED = 2;

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
     * Inserting formData passed from new post to 3 tables
     * 1. cms_blog_post, 2. cms_blog_post_to_category, 3. cms_blog_tag
     * @param $formData
     * @param Zend_Db_Table_Abstract $modelBlogPostToCategory
     * @param Zend_Db_Table_Abstract $modelBlogTag
     * @return int postId | id of newly created post
     */
    public function createPostCategoryTagData($formData,
                                              ZendDbAbstract $modelBlogPostToCategory,
                                              ZendDbAbstract $modelBlogTag)
    {
        // models array variables
        $postData = array();
        $formData['date_published'] = date('Y-m-d H:i:s', strtotime($formData['date_published']));

        $postDataFields = $this->info(ZendDbAbstract::COLS);
        if (!empty($formData) && count($formData) > 0) {

            // Post inserting
            foreach ($formData as $field => $value) {
                if (in_array($field, $postDataFields)) {
                    $postData[$field] = $value;
                }
            }
            $postId = $this->insert($postData);

            // Categories inserting
            foreach ($formData['categories'] as $key => $value) {
                $postToCategoryData = array('category_id' => $value, 'post_id' => $postId);
                $modelBlogPostToCategory->insert($postToCategoryData);
            }

            // Tags inserting
            foreach (explode(',', $formData['tags']) as $key => $value) {
                $postTagData = array('post_id' => $postId, 'title' => $value);
                $modelBlogTag->insert($postTagData);
            }
        }

        return $postId;
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