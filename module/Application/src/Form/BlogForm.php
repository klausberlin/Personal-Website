<?php
/**
 * User: Alice in wonderland
 * Date: 01.07.2017
 * Time: 21:13
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class BlogForm extends Form
{

    public function __construct()
    {
        parent::__construct('blog-post');

        $this->setAttribute('method','post');

        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {

        $this->add([
            'type' => 'text',
            'name' => 'title',
            'options' => [
                'label' => 'Title'
            ],
            'attributes' => [
                'id' => 'title'
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'content',
            'options' => [
                'label' => 'Content'
            ],
            'attributes' => [
                'id' => 'content'
            ],

        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit-post',
            'options' => [
                'id' => 'submit',
            ],
            'attributes' => [
                'value' => 'Submit'
            ]
        ]);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);


        $inputFilter->add([
                'name' => 'title',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]
        );

        $inputFilter->add([
                'name' => 'content',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 4096
                        ],
                    ],
                ],
            ]
        );



    }


}