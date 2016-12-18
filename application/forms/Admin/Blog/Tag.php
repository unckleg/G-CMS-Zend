<?php

class Form_Admin_Blog_Tag extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction('');

        $categoryName = new Zend_Form_Element_Text('title');
        $categoryName->addFilter('StringTrim')
            ->addValidator('StringLength', false, array('min' => 2, 'max' => 255))
            ->setAttribs(array('class' => 'form-control', 'placeholder' => 'Unseite ime oznake', 'required' => ''))
            ->setRequired(false);
        $this->addElement($categoryName);

        $this->setElementDecorators(array(array('ViewHelper'), array('Errors')))
            ->setDecorators(array(array('ViewScript', array(
                    'viewScript' => 'admin/blog/form/_tag.phtml',
                )),
            ));
    }
}