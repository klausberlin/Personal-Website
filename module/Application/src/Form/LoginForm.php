<?php
/**
 * User: Alice in wonderland
 * Date: 15.06.2017
 * Time: 10:58
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct('auth-form');

        $this->setAttribute('method','post');

        $this->addElement();
        $this->addInputFilter();
    }
    public function addElement()
    {
        $this->add([
           'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-Mail',
            ],
        ]);

        $this->add([
           'type' => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
           'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Submit',
            ],
        ]);

    }
    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
           'name' => 'email',
            'require' => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
           'name' => 'password',
            'require' => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 64
                    ],
                ],
            ],
        ]);
    }

}