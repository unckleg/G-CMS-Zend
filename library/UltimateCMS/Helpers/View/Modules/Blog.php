<?php

use UltimateCMS_Model_ORM as ORM;

class Blog extends Zend_View_Helper_Abstract
{
    protected $_post;
    protected $_comment;

    /**
     * Self point method so we can access all other
     * functions outside the helper. We can achieve modularity in application
     * <b>
     * @uses $this->getHelper('HelperName')->methodName()
     * This is how to use the same just call method getHelper outside the class
     * And continue in chain with methods.
     * </b>
     * @return $this
     */
    public function blog()
    {
        return $this;
    }

    public function commentCount($postId)
    {
        $modelBlogPost = $this->_getBlogPostModel();
        $modelBlogComment = $this->_getBlogPostCommentModel();

        // Find post by primary key
        $postExist = $modelBlogPost->find($postId);

        // Check if post exist calculate
        if (count($postExist) > 0) {
            $comments = ORM::Mapper_Search($modelBlogComment, array(
                'filters' => array(
                    'post_id' => $postId
                )
            ));
        }

        // Prepare variable for method returning
        $finalCount = (count($comments) > 0) ? count($comments) : 0;

        return $finalCount;
    }

    // Lazy tables loading
    protected function _getBlogPostModel()
    {
        if (!$this->_post) {
            $this->_comment = new Model_Admin_Blog_BlogComment();
        }
        return $this->_comment;
    }

    protected function _getBlogPostCommentModel()
    {
        if (!$this->_comment) {
            $this->_comment = new Model_Admin_Blog_BlogPost();
        }
        return $this->_comment;
    }
}