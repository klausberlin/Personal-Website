<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Application\Service\GithubManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    private $githubData;

    public function __construct()
    {

        $this->githubData = new GithubManager();

    }

    public function indexAction()
    {
        return new ViewModel([
        ]);
    }

    // This action displays the Thank You page. The user is redirected to this
    // page on successful mail delivery.
    public function thankYouAction()
    {
        return new ViewModel();
    }


    public function aboutAction()
    {

        $myRepos = $this->githubData->getMyRepos();
        return new ViewModel([
            'myRepos' => json_decode($myRepos),
        ]);
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
