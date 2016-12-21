<?php

// Model classes
use UltimateCMS_Model_ORM as ORM,
    Zend_Controller_Request_Http as Request,
    Model_Admin_Blog_BlogAuthor as BlogAuthor,
    Model_Admin_Blog_BlogCategory as BlogCategory,
    Model_Admin_Blog_BlogPostToCategory as BlogPostToCategory,
    Model_Admin_Blog_BlogPost as BlogPost,
    Model_Admin_Blog_BlogTag as BlogTag,
    Model_Admin_Blog_BlogComment as BlogComment;

// Form classes
use Form_Admin_Blog_Category as FormCategory,
    Form_Admin_Blog_Tag as FormTag,
    Form_Admin_Blog_Post as FormPost;

class Admin_BlogController extends UltimateCMS_Controller_Abstract
{
    private $_widhtXL = 1060;
    private $_heightXL = 1060;

    private $_widhtL = 748;
    private $_heightL = 748;

    private $_widhtS = 100;
    private $_heightS = 100;

    protected $_redirector;
    protected $_flashMessenger;
    protected $_systemMessages;

    public function init()  {
        $this->_redirector = $this->getHelper('Redirector');
        $this->_flashMessenger = $this->getHelper('FlashMessenger');
        $this->_systemMessages = array(
            'success' => $this->_flashMessenger->getMessages('success'),
            'errors' => $this->_flashMessenger->getMessages('errors')
        );

        $this->view->systemMessages = $this->_systemMessages;
    }

    /**
     * @param Model_Admin_Blog_BlogPost $modelBlogPost
     */
    public function indexAction(BlogPost $modelBlogPost)
    {
        // All posts fetching
        $allPosts = ORM::Mapper_SearchAll($modelBlogPost);

        $this->view->posts = $allPosts;
    }

    /**
     * @param int $categoryId
     * @param Model_Admin_Blog_BlogCategory $modelBlogCategory
     * @param Form_Admin_Blog_Category $form
     * @param Zend_Controller_Request_Http $request
     */
    public function categoryAction($categoryId, BlogCategory $modelBlogCategory,
                                                FormCategory $form,
                                                Request $request )
    {
        // All categories fetching
        $allCategories = ORM::Mapper_SearchAll($modelBlogCategory);

        if ($request->isXmlHttpRequest()) {
            $category = $modelBlogCategory->find($categoryId)->current();

            if (count($category) > 0 && $category !== NULL) {
                $data = $category->name;
                $this->_helper->json($data);
            }
        }

        if ($request->isPost()) {
            try {

                if ($request->getPost('task') == 'update') {

                    if ($request->getPost('id') !== NULL && $request->getPost('id') !== '') {
                        $categoryId = $request->getPost('id');
                    } else {
                        throw new Exception('ID broj kategorije nije prosledjen.');
                    }

                    // Get form data
                    $formData = $form->getValues();
                    $formData['status'] = BlogCategory::STATUS_VISIBLE;
                    $formData['name'] = $request->getPost('name');

                    // Insert to database
                    $modelBlogCategory->editCategory($categoryId, $formData);

                    $this->_flashMessenger->addMessage('Kategorija je uspešno izmenjena.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_blog',
                            'action' => 'category',
                        ), 'default', true);

                } elseif ($request->getPost('task') == 'create') {

                    // Check form is valid
                    if (!$form->isValid($request->getPost())) {
                        var_dump($form->getMessages()); exit();
                    }

                    // Get form data
                    $formData = $form->getValues();
                    $formData['status'] = BlogCategory::STATUS_VISIBLE;

                    // Insert to database
                    $modelBlogCategory->addCategory($formData);

                    $this->_flashMessenger->addMessage('Kategorija je uspešno kreirana.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_blog',
                            'action' => 'category',
                        ), 'default', true);
                }

            } catch (Exception $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }

        }

        $this->view->form = $form;
        $this->view->categories = $allCategories;
    }

    /**
     * @param int $tagId
     * @param Model_Admin_Blog_BlogTag $modelBlogTag
     * @param Form_Admin_Blog_Tag $form
     * @param Zend_Controller_Request_Http $request
     */
    public function tagAction($tagId, BlogTag $modelBlogTag,
                                      FormTag $form,
                                      Request $request)
    {
        // All tags fetching
        $allTags = ORM::Mapper_SearchAll($modelBlogTag);

        if ($request->isXmlHttpRequest()) {
            $tag = $modelBlogTag->find($tagId)->current();

            if (count($tag) > 0 && $tag !== NULL) {
                $data = $tag->title;
                $this->_helper->json($data);
            }
        }

        if ($request->isPost()) {
            try {

                if ($request->getPost('task') == 'update') {

                    if ($request->getPost('id') !== NULL && $request->getPost('id') !== '') {
                        $tagId = $request->getPost('id');
                    } else {
                        throw new Exception('ID broj oznake nije prosledjen.');
                    }

                    // Get form data
                    $formData = $form->getValues();
                    $formData['title'] = $request->getPost('title');

                    // Insert to database
                    $modelBlogTag->editTag($tagId, $formData);

                    $this->_flashMessenger->addMessage('Oznaka je uspešno izmenjena.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_blog',
                            'action' => 'tag',
                        ), 'default', true);

                } elseif ($request->getPost('task') == 'create') {

                    // Check form is valid
                    if (!$form->isValid($request->getPost())) {
                        var_dump($form->getMessages()); exit();
                    }

                    // Get form data
                    $formData = $form->getValues();

                    // Insert to database
                    $modelBlogTag->addTag($formData);

                    $this->_flashMessenger->addMessage('Oznaka je uspešno kreirana.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_blog',
                            'action' => 'tag',
                        ), 'default', true);
                }

            } catch (Exception $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }

        }

        $this->view->form = $form;
        $this->view->tags = $allTags;
    }

    /**
     * @param Model_Admin_Blog_BlogComment $modelBlogComment
     * @param Zend_Controller_Request_Http $request
     */
    public function commentAction(BlogComment $modelBlogComment, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $commentId = $request->getParam('pk');
            $commentValue = $request->getParam('value');

            if ($commentId !== NULL && $commentId !== '') {
                $comment = $modelBlogComment->find($commentId)->current();
                $comment->comment = $commentValue;
                $comment->save();
            }

        }

        // Filtered comments fetching
        $approvedComments = ORM::Mapper_Search($modelBlogComment, array(
            'filters' => array(
                'mark_approved' => BlogComment::STATUS_APPROVED,
                'mark_read' => BlogComment::COMMENT_READED
            ),
            'order' => array('date_created' => ORM::COLUMN_ASC)
        ));

        $newComments = ORM::Mapper_Search($modelBlogComment, array(
           'filters' => array(
               'mark_read' => BlogComment::COMMENT_PENDING
           ),
           'order' => array('date_created' => ORM::COLUMN_ASC)
        ));

        $cache = Zend_Registry::get('Cache');
        $cacheKey = 'approvedComments';

        if (empty($cacheKey) || ($approvedComments = $cache->load($cacheKey)) == false) {
            $cache->save($approvedComments, $cacheKey);
        }

        $approvedCommentsCached = $cache->load($cacheKey);

        $this->view->newcomments = $newComments;
        $this->view->approvedcomments = $approvedCommentsCached;
    }

    /**
     * @param Form_Admin_Blog_Post $form
     * @param Model_Admin_Blog_BlogPost $modelBlogPost
     * @param Model_Admin_Blog_BlogCategory $modelBlogCategory
     * @param Model_Admin_Blog_BlogTag $modelBlogTag
     * @param Model_Admin_Blog_BlogAuthor $modelBlogAuthor
     * @param Model_Admin_Blog_BlogPostToCategory $modelBlogPostToCategroy
     * @param Zend_Controller_Request_Http $request
     */
    public function createAction(FormPost $form, BlogPost $modelBlogPost, BlogCategory $modelBlogCategory,
                                                 BlogTag $modelBlogTag,   BlogAuthor $modelBlogAuthor,
                                                 BlogPostToCategory $modelBlogPostToCategroy,
                                                 Request $request)
    {

    }

    public function statusAction()
    {

    }
}