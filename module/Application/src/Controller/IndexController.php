<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Application\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\MailSender;

class IndexController extends AbstractActionController
{

    private $mailSender;

    public function __construct(MailSender $mail)
    {
        $this->mailSender = $mail;
    }

    public function indexAction()
    {
        $form = new Form\ContactForm();
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();
                //save data
                $email = $data['email'];
                $subject = $data['subject'];
                $body = $data['body'];

                // Send E-mail
                if (!$this->mailSender->sendMail('pawelklaus@hotmail.de', $email,
                    $subject, $body)
                ) {
                    // In case of error, redirect to "Error Sending Email" page
                    return $this->redirect()->toRoute('application',
                        ['action' => 'sendError']);
                }

                // Redirect to "ThankYou" page
                return $this->redirect()->toRoute('application',
                    ['action' => 'thankYou']);
            }
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    // This action displays the Thank You page. The user is redirected to this
    // page on successful mail delivery.
    public function thankYouAction()
    {
        return new ViewModel();
    }

    // This action displays the Send Error page. The user is redirected to this
    // page on mail delivery error.
    public function sendErrorAction()
    {
        return new ViewModel();
    }
    /*//This action displays the login form.
    //The user redirected after login.
    public function loginAction()
    {
        $loginForm = new Form\LoginForm();

        if($this->getRequest()->isPost()){
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $loginForm->setData($data);

            // Validate form
            if ($loginForm->isValid()) {


            }
        }
        return new ViewModel([
           'form' => $loginForm
        ]);
    }*/





}
