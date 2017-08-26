<?php
/**
 * User: Alice in wonderland
 * Date: 01.07.2017
 * Time: 23:18
 */

namespace Application\Service\Factory;


use Application\Service\PostManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class PostManagerFactory implements FactoryInterface
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
        //getting the entity manager service and save it to the variable
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        //inject the entity manager to its object
        return new PostManager($entityManager);
    }
}