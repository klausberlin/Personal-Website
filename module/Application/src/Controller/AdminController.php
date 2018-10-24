<?php
/**
 * User: Alice in wonderland
 * Date: 16.06.2017
 * Time: 15:20
 */

namespace Application\Controller;


use Application\Entity\Post;
use Application\Form\BlogForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    /**
    * @var AuthenticationService $authentication
    */
    private $authentication;

    /**
    * Entity manager.
    * @var \Doctrine\ORM\EntityManager
    */
    private $entityManager;

    /**
    * Post Manager
    * @var \Application\Service\PostManager
    */
    private $postManager;

    public function __construct($authentication, $entityManager, $postManager)
    {
        $this->authentication = $authentication;
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
    }
    public function onDispatch(MvcEvent $e)
    {
        if($this->authentication->hasIdentity() != true)
        {
            $this->redirect()->toRoute('home');
        }
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        //$postId = $this->params()->fromRoute('id', -1);
        $repository = $this->entityManager->getRepository(Post::class)->findBy(
            ['status'=> Post::STATUS_PUBLISHED ],
            ['dateCreated'=>'DESC'], 50);

        return new ViewModel([
            'posts' => $repository
        ]);
    }

    public function addAction()
    {
        //instantiate the Blog form to create a new blogpost
        $form = new BlogForm();

        //get the repository(entity) with the entityManager

        if($this->getRequest()->isPost()){

            //get the params from the form until the from send a post request
            $data = $this->params()->fromPost();

            //set data for the form
            //passes data on to the composed input filter.
            $form->setData($data);

            if($form->isValid()){
                $this->postManager->addNewPost($data);

                return $this->redirect()->toRoute('admin',['action' => 'index']);
            }


        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $form = new BlogForm();

        $postId = (int)$this->params()->fromRoute('id');

        if($postId < 0){
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $repo = $this->entityManager->getRepository(Post::class)->findOneById($postId);

        if($repo < null){
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {

            //save data from post request
            $data = $this->params()->fromPost();

            //Fill the form with data
            $form->setData($data);

            if($form->isValid()){

                $data = $form->getData();

                $this->postManager->updatePost($repo,$data);

                return $this->redirect()->toRoute('admin',['action' => 'index']);

            }

        } else {

            $data = [
                'title' => $repo->getTitle(),
                'content' => html_entity_decode($repo->getContent())
            ];

            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {

        //save the id from the blogpost
        $postId = (int)$this->params()->fromRoute('id');

        if($postId < 0){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        //get the record from the database
        $repository = $this->entityManager->getRepository(Post::class)->findOneById($postId);

        if($repository < 0){
            $this->getResponse()->setStatusCode(404);
            return;
        }

        //send record to postManager to remove the record
        $this->postManager->removePost($repository);


        $this->redirect()->toRoute('admin',['action'=>'index']);


        return new ViewModel();
    }

    public function mainAction()
    {

        return new ViewModel();

    }
}