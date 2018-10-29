<?php
/**
 * User: Alice in wonderland
 * Date: 16.06.2017
 * Time: 15:28
 */

namespace Application\Controller\Factory;


use Application\Controller\AdminController;
use Application\Service\PostManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdminControllerFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authentication = $container->get('authentication');
        $entityManager  = $container->get('doctrine.entitymanager.orm_default');
        $postManager    = $container->get(PostManager::class);

        return new AdminController($authentication, $entityManager, $postManager);
    }
}