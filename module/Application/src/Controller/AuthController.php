<?php
/**
 * User: Alice in wonderland
 * Date: 15.06.2017
 * Time: 19:08
 */

namespace Application\Controller;


use Application\Form\LoginForm;
use Application\Service\AuthManager;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{

    /**
     * @var AuthManager $authManager
     */
    private $authManager;

    /**
     * Constructor.
     */
    public function __construct($authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Authenticates user given email address and password credentials.
     * @throws \Exception
     */
    public function indexAction()
    {
        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successfull login.
     /*   $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }*/

        // Create login form
        $form = new LoginForm();/*
        $form->get('redirect_url')->setValue($redirectUrl);*/

        // Store login status.
        $isLoginError = false;

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Perform login attempt.
                $result = $this->authManager->lostandfound($data['email'], $data['password']);

                // Check result.
                if ($result->getCode() == Result::SUCCESS) {

                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        // The below check is to prevent possible redirect attack
                        // (if someone tries to redirect user to another domain).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost()!=null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
                    if(empty($redirectUrl)) {
                        return $this->redirect()->toRoute('admin');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }
    public function logoutAction()
    {
        $this->authManager->logout();


        return $this->redirect()->toRoute('home');
    }

}
