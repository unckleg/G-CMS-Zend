<?php

class Form_Admin_Page extends Zend_Form
{
    protected $_pageLayout;
    protected $_task;
    protected $_pagePhoto;

    public function __construct($page, $task = null, $photo = null, $options = null) {
        $this->_pageLayout = $page['page_layout'];
        isset($task) ? $this->_task = 'update' :  $this->_task = 'save';
        isset($photo) ? $this->_pagePhoto = $photo : null;

        parent::__construct($options);
    }
    
    public function init() 
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction('');
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        
        $validatorRequired = new Zend_Validate_NotEmpty();

        $titleAdd = new Zend_Form_Element_Text('title');
        $titleAdd->addFilter('StringTrim')
            ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
            ->setAttribs(array('class' => 'form-control', 'placeholder' => 'Insert title of your page', 'required' => ''))
            ->setRequired(true);
        $this->addElement($titleAdd);

        $text = new Zend_Form_Element_Textarea('text');
        $text->setRequired(true)
            ->setAttribs(array('class' => 'form-control', 'id' => 'editor'));
        $this->addElement($text);
        
        $seoTitle = new Zend_Form_Element_Text('seo_title');
        $seoTitle->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setAttribs(array('class' => 'form-control input-sm', 'placeholder' => 'Insert seo title',))
                ->setRequired(false);
        $this->addElement($seoTitle);

        $seoKeywords = new Zend_Form_Element_Text('seo_keywords');
        $seoKeywords->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 500))
                ->setAttribs(array('class' => 'form-control input-sm', 'placeholder' => 'Insert seo description',))
                ->setRequired(false);
        $this->addElement($seoKeywords);

        $seoDescription = new Zend_Form_Element_Text('seo_description');
        $seoDescription->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 500))
                ->setAttribs(array('class' => 'form-control input-sm', 'placeholder' => 'Insert seo keywords',))
                ->setRequired(false);
        $this->addElement($seoDescription);
        
        $keywords = Zend_Registry::get('config')->keywords;
        $pagePhoto = new Zend_Form_Element_File('page_photo');
        $pagePhoto->addValidator('Count', true, 1)
                ->addValidator('MimeType', true, array('image/jpeg', 'image/gif', 'image/png'))
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 150,
                    'minheight' => 150,
                    'maxwidth' => 4000,
                    'maxheight' => 4000,
                    ))
                ->addValidator('FilesSize', false, array('max' => '10MB'))
                ->setValueDisabled(true)
                ->setRequired(false);
        $pagePhoto->getTransferAdapter()->setOptions(array('useByteString' => false));
        $pagePhoto->addFilter('Rename', array ('target' => APP_PUBLIC . '/uploads/pages/'.date("Y-m-d-H-i-s") . '-' . $keywords . '.jpg', 'overwrite' => true ));
        $this->addElement($pagePhoto);
        
        $this->setElementDecorators(array(array('ViewHelper'), array('Errors')))
                ->setDecorators(array(array('ViewScript', array(
                    'viewScript' => 'admin/page/form/_page.phtml', 
                    'layout' => $this->_pageLayout,
                    'task' => $this->_task,
                    'photo' => $this->_pagePhoto
                )),
        ));
    }
}

