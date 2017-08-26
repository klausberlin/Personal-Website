<?php
/**
 * User: Alice in wonderland
 * Date: 31.05.2017
 * Time: 14:55
 */

namespace Application\Controller\Factory;


use Application\Controller\BlogController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BlogControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null)
    {


        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        // Instantiate the controller and inject dependencies to its controller
        $blogController =  new BlogController($entityManager);
        return $blogController;
    }
}