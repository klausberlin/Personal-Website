<?php
/**
 * User: Alice in wonderland
 * Date: 30.06.2017
 * Time: 12:08
 */

namespace Application\Controller;


use Application\Entity\Post;
use Application\Service\GithubManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    /**
    * Entity manager.
    * @var \Doctrine\ORM\EntityManager
    */
    private $entityManager;


    //constructor method which is used to inject the Doctrine entity manager
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $post = $this->entityManager->getRepository(Post::class)->findBy(
            ['status'=> Post::STATUS_PUBLISHED ],
            ['dateCreated'=>'DESC'],
            50);


        return new ViewModel([
            'post' => $post,
        ]);
    }

    public function viewAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'view' => $post
        ]);
    }

    public function adminAction()
    {
        return new ViewModel();
    }

}