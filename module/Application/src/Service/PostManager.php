<?php
/**
 * User: Alice in wonderland
 * Date: 01.07.2017
 * Time: 23:17
 */

namespace Application\Service;


use Application\Entity\Post;

class PostManager
{
    /**
    * Entity manager.
    * @var \Doctrine\ORM\EntityManager
    */
    private $entityManager;


    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addNewPost($data)
    {
        // Create new Post entity.
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus(2);
        $post->setDateCreated(date("Y-m-d h:i:sa"));

        // Add the entity to entity manager.
        $this->entityManager->persist($post);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removePost($data)
    {
        //remove data in database
        $this->entityManager->remove($data);

        //Apply changes to database
        $this->entityManager->flush();
    }

    public function updatePost($post,$data)
    {
        $post->setTitle($data['title']);
        $post->setContent($data['content']);

        $this->entityManager->flush();


    }
}